<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(){
        $userId = Auth::id() ?? 1;

        $orderLines = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->leftJoin('products', 'order_items.item_id', '=', 'products.id')
            ->where('orders.user_id', $userId)
            ->select(
                'order_items.id as order_item_id',
                'order_items.quantity',
                'order_items.price',
                'order_items.status as item_status',
                'orders.order_number',
                'orders.tracking_number',
                'orders.created_at as order_date',
                'products.name as product_name',
                'products.image as product_image'
            )
            ->orderBy('orders.created_at', 'desc')
            ->get();

        $groupedOrders = $orderLines->groupBy('order_number');

        return view('order', compact('groupedOrders'));
    }
}
