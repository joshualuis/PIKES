<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;
use App\Models\User;
use App\Models\Deceased;
use App\Models\Product;
use App\Models\Announcement;
use App\Models\Notification;
use App\Models\Package;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Orderline;
use Log;
use Validator;
use DB;

class MobileController extends Controller
{

    public function customerregister(Request $request)
    {

        // Log::info($request);

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'unique:users,email'],
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'failed'], 200);
        }

        $fullName = $request->fname . ' ' . $request->lname;

        $user = User::create([
            'name' => $fullName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => "customer",
        ]);

        $userId = $user->id;
        // Log::info($user);

        // Save the uploaded images
        $custImage = base64_decode($request->customer_image);
        $custValidId = base64_decode($request->valid_id);
        $custGcashQr = base64_decode($request->gcash_qr);

        $length = 10;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        
        $custImageString = '';
        for ($i = 0; $i < $length; $i++) {
            $custImageString .= $characters[random_int(0, $charactersLength - 1)];
        }

        $custValidIdString = '';
        for ($i = 0; $i < $length; $i++) {
            $custValidIdString .= $characters[random_int(0, $charactersLength - 1)];
        }

        $custGcashQrString = '';
        for ($i = 0; $i < $length; $i++) {
            $custGcashQrString .= $characters[random_int(0, $charactersLength - 1)];
        }

        $custImageName = time() .  $custImageString . 'custimage.jpg';
        $custValidIdName = time() . $custValidIdString. 'custvalidid.jpg';
        $custGcashQrName = time() . $custGcashQrString . 'custgcashqr.jpg';

        $custImageLoc = 'images/' . $custImageName;
        $custValidIdLoc = 'images/' . $custValidIdName;
        $custGcashQrLoc = 'images/' . $custGcashQrName;

        file_put_contents(public_path($custImageLoc), $custImage);
        file_put_contents(public_path($custValidIdLoc), $custValidId);
        file_put_contents(public_path($custGcashQrLoc), $custGcashQr);

        $customer = Customer::create([
            'userID' => $userId,
            'fname' => $request->fname,
            'lname' => $request->lname,
            'age' => $request->age,
            'sex' => $request->sex,
            'address' => $request->address,
            'contact' => $request->contact,
            'idtype' => $request->idtype,
            'custimage' => $custImageLoc,
            'custvalidid' => $custValidIdLoc,
            'custgcashqr' => $custGcashQrLoc,
        ]);

        // Log::info($customer);

        return response()->json(['message' => 'success'], 200);
    }

    public function getProfile($id)
    {
        // Log::info($id);
        // Retrieve the user by the provided ID
        $user = Customer::where('id', $id)->first();
        // Log::info($user);

        // Check if the user exists
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Return the user profile information
        return response()->json($user);
    }

    public function deceasedList($id)
    {
        Log::info($id);
        $deceaseds = Deceased::where('customerID', $id)->get();
        Log::info($deceaseds);
        return response()->json($deceaseds);
    }

    public function addDeceased(Request $request)
    {
        $deceased = Deceased::create([
            'customerID' => $request->customerID,
            'fname' => $request->fname,
            'mname' => $request->mname,
            'lname' => $request->lname,
            'relationship' => $request->relationship,
            'causeofdeath' => $request->causeofdeath,
            'sex' => $request->sex,
            'religion' => $request->religion,
            'age' => $request->age,
            'dateofbirth' => $request->dateofbirth,
            'dateofdeath' => $request->dateofdeath,
            'placeofdeath' => $request->placeofdeath,
            'citizenship' => $request->citizenship,
            'address' => $request->address,
            'civilstatus' => $request->civilstatus,
            'occupation' => $request->occupation,
            'namecemetery' => $request->namecemetery,
            'addresscemetery' => $request->addresscemetery,
            'nameFather' => $request->nameFather,
            'nameMother' => $request->nameMother,
        ]);

        $deceasedId = $deceased->id;

        Log::info($deceasedId);

        return response()->json(['message' => 'success'], 200);
    }

    public function productsList()
    {
        $products = Product::all();
        // Log::info($products);
        return response()->json($products);
    }

    public function CasketproductsList()
    {
        $products = Product::where('category', 'Caskets')->get();
        // Log::info($products);
        return response()->json($products);
    }

    public function DressingproductsList()
    {
        $products = Product::where('category', 'Dressings')->get();
        // Log::info($products);
        return response()->json($products);
    }

    public function FlowerproductsList()
    {
        $products = Product::where('category', 'Flowers')->get();
        // Log::info($products);
        return response()->json($products);
    }

    public function UrnproductsList()
    {
        $products = Product::where('category', 'Urns')->get();
        // Log::info($products);
        return response()->json($products);
    }

    public function announcementsList()
    {
        $announcements = Announcement::orderBy('updated_at', 'desc')->get();
        // Log::info($announcements);
        return response()->json($announcements);
    }

    public function notificationsList()
    {
        $notifications = Notification::orderBy('updated_at', 'desc')->get();
        // Log::info($notifications);
        return response()->json($notifications);
    }

    public function packagesList()
    {
        $packages = Package::all();
        // Log::info($packages);
        return response()->json($packages);
    }

    public function EmbalmingpackagesList()
    {
        $packages = Package::where('category', 'Embalming')->get();
        // Log::info($packages);
        return response()->json($packages);
    }

    public function CremationpackagesList()
    {
        $packages = Package::where('category', 'Cremation')->get();
        // Log::info($packages);
        return response()->json($packages);
    }

    public function AllinpackagesList()
    {
        $packages = Package::where('category', 'Allin')->get();
        // Log::info($packages);
        return response()->json($packages);
    }

    public function productInfo($id)
    {
        // Log::info($id);
        $products = Product::where('id', $id)->first();
        // Log::info($products);
        return response()->json($products);
    }

    public function packageInfo($id)
    {
        // Log::info($id);
        $packages = Package::where('id', $id)->first();
        // Log::info($packages);
        return response()->json($packages);
    }

    public function addToCart(Request $request)
    {
        $carts = Cart::create([
            'customerID' => $request->customerID,
            'productID' => $request->productID,
        ]);

        Log::info($carts);

        return response()->json(['message' => 'success'], 200);
    }

    public function cartList($id)
    {
        $customer = Cart::where('customerID', $id)->get();
        $products = $customer->pluck('productID');
        $cartData = [];

        foreach ($products as $product) {
            $cart = Product::where('id', $product)->first();
            $cartData[] = $cart;
        }
        $cartData = collect($cartData)->sortBy('id')->values()->all();
        return response()->json($cartData);
    }

    public function cartTotal($id)
    {
        $customer = Cart::where('customerID', $id)->get();
        $products = $customer->pluck('productID');
        $cartData = [];
        $totalPrice = 0;

        foreach ($products as $product) {
            $cart = Product::where('id', $product)->first();
            $cartData[] = $cart;
            $totalPrice += $cart->price;
        }

        return response()->json(['message' => $totalPrice], 200);
    }

    public function cartDelete(Request $request)
    {
        $item= Cart::where('customerID', $request->customerID)
                        ->where('productID', $request->productID)->first();
        $item->delete();

        return response()->json(['message' => 'success'], 200);
    }

    public function checkout(Request $request)
    {

        $customer = Cart::where('customerID', $request->customerID)->get();
        $products = $customer->pluck('productID');
        $totalPrice = 0;

        foreach ($products as $product) {
            $cart = Product::where('id', $product)->first();
            $totalPrice += $cart->price;
        }

        $order = Order::create([
            'customerID' => $request->customerID,
            'name' => $request->name,
            'address' => $request->address,
            'contact' => $request->contact,
            'total' => $totalPrice,
            'MOP' => $request->modeofpayment,
            'POP' => $request->proofofpayment,
            'type' => 'Products',
            'status' => 'pending'
        ]);
        $orderId = $order->id;

        foreach ($products as $product) {
            $item = Product::where('id', $product)->first();
            $orderline = Orderline::create([
                'orderID' => $orderId,
                'name' => $item->name,
                'image' => $item->img,
                'price' => $item->price
            ]);
        }

        $customer = Cart::where('customerID', $request->customerID)->delete();

        return response()->json(['message' => $order],200);
    }

    public function orderList($id)
    {
        $orders = Order::where('customerID', $id)->orderBy('updated_at', 'desc')->get();;
        return response()->json($orders);
    }

    
}
