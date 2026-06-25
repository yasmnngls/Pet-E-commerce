<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $orders = Order::with(['items.item', 'items.seller.sellerApplication'])
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->get();

        return view('order', compact('orders'));
    }
}
