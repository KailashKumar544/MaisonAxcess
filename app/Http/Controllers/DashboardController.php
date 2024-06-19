<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $orders = $user->orders()->get();
        
        return view('dashboard', compact('orders'));
    }

    public function show($orderId){
        $user = auth()->user();
        $order_details = $user->orders()->find($orderId);
        
        return view('view_order', compact('order_details', 'orderId'));
    }
}
