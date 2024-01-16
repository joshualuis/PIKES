<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Order;
use Yajra\Datatables\Datatables;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $orders = Order::all();

        if ($request->ajax()) {
            $data = $orders;
            return Datatables::of($data)
                    ->addIndexColumn()
                    // ->addColumn('statusAct', 'admin.orders.statusAction')
                    ->addColumn('action', 'admin.orders.action')
                    // ->addColumn('checkbox', function($row){
                    //     return '<input type="checkbox" name="sl_checkbox" data-id="'.$row['id'].'"><label></label>';
                    // })
                    // ->rawColumns(['checkbox','action'])
                    // ->rawColumns(['statusAct', 'action'])
                    ->make();
        }

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if($request->addressRadio == 0){
        }else{
            $request->validate([
                'address' => 'required|string|max:255',
                'contact' => 'required|numeric',
                ]);
        }

        $user = auth()->user(); // Assuming the user is authenticated
        // Retrieve the user's cart
        $cart = $user->cart;

        // Check if the user has a cart
        if ($cart) {

            if($request->addressRadio == 0){
                $address = $user->customer->address;
                $contact = $user->customer->contact;
            }
            else{
                $address = $request->address;
                $contact = $request->contact;
            }

            if($request->MOP = "cod"){
                $POP = null;
            }
            else{
                if($request->hasFile('POP')){
                    $img_path = $request->file('POP')->getClientOriginalName();
                    $request->file('POP')->storeAs('public/images', $img_path);
                    $POP = 'storage/images/'.$img_path;
                }
            }

            // Create a new order
            $order = Order::create([
                'user_id' => $user->id,
                'name' => auth()->user()->name,
                'address' => $address,
                'contact' => $contact,
                'total_price' => $cart->total_price,
                'discounted_price' => 0,
                'MOP' => $request->MOP,
                'POP' => $POP,
                'type' => 'products',
                'status' => 'Placed',
                // Add other order-related fields as needed
            ]);
    
            // Attach products from the user's cart to the order
            $productIdArray = $cart->products->pluck('id')->toArray();
            $quantitiesArray = $cart->products->pluck('pivot.quantity')->toArray();
            
            $syncData = [];
            
            foreach ($productIdArray as $index => $productId) {
                $syncData[$productId] = ['quantity' => $quantitiesArray[$index]];
            }
            
            $order->products()->sync($syncData);
            
            // Update the order's total price based on the attached products
            $order->updateTotalPrice();
    
            // Optionally, you can clear the user's cart after checkout
            $cart->products()->detach();
            $cart->updateTotalPrice(); // Make sure to implement this method in your Cart model
            session()->forget('cart');
            // Redirect or return a response as needed
            // return view('customer.confirmation', compact('order')); 
            return redirect()->route('confirmation', ['order' => $order->id]);
        } else {
            // Handle the case where the user does not have a cart
            return redirect()->back()->with('error', 'User does not have a cart');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $order = Order::where('id', $id)->first();
        $user = $order->user->id;
        $count = Order::whereHas('user', function ($query) use ($user) {
            $query->where('id', $user);
        })->count();
        return view('admin.orders.edit',compact('order', 'count'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $order = Order::find($id);
        
        if($request->status == 2){
            $order->status = 'Preparing';
        }
        elseif($request->status == 3){
            $order->status = 'Shipped';
        }
        elseif($request->status == 4){
            $order->status = 'Received';
        }
        else{
            $order->status = 'Placed';
        }


        $order->update();

        return redirect()->back()->with('status', 'Status Updated');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function forward(Order $order)
    {
        if($order->status == 'Placed'){
            $order->status = 'Preparing';
        }
        elseif($order->status == 'Preparing'){
            $order->status = 'Shipped';
        }
        elseif($order->status == 'Shipped'){
            $order->status = 'Received';
        }

        // dd($order->status);

        $order->update();

        return redirect()->back();
    }

    public function backward(Order $order)
    {
        if($order->status == 'Preparing'){
            $order->status = 'Placed';
        }
        elseif($order->status == 'Shipped'){
            $order->status = 'Preparing';
        }
        elseif($order->status == 'Received'){
            $order->status = 'Shipped';
        }

        $order->update();

        return redirect()->back();
    }
}
