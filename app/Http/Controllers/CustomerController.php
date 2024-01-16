<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;

class CustomerController extends Controller
{
    public function profile(Customer $customer){
       return view('customer.profile.profile', compact('customer'));
    }
}
