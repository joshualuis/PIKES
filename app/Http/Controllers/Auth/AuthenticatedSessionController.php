<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // dd("HAHA");
        $request->authenticate();

        $request->session()->regenerate();


        if(Auth::user()->role == 'admin'){
            // return View('admin.dashboard');
            return redirect()->route('admin.dashboard');
        }
        elseif(Auth::user()->role == 'employee')
        {
            return view('');
        }
        else{

            $user = auth()->user();
            // Retrieve the user's cart. If the user doesn't have a cart, create one.
            $cart = $user->cart ?? $user->cart()->create();
            $kart = session()->get('cart', []);
            $kart[$cart->id] = [
                'id' => $cart->id,
                'total_price' => $cart->total_price,
                'items' => $cart->products,
            ];
            session()->put('cart', $kart);
            
            return redirect()->route('customer.products');
        }

        // return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
