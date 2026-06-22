<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OtsSellerOrderController extends Controller
{
    public function index()
    {
        $sellerId = Auth::id() ?? 1;

        // Advanced cross-table compilation matching structural requirements
        $orders = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('addresses', 'orders.address_id', '=', 'addresses.id')
            ->join('products', 'order_items.item_id', '=', 'products.id')
            ->where('order_items.seller_id', $sellerId)
            ->select(
                'order_items.id as order_item_id',
                'order_items.quantity',
                'order_items.status as item_status',
                'orders.order_number',
                'orders.tracking_number',
                'users.name as buyer_name',
                'addresses.street',
                'addresses.barangay',
                'addresses.city',
                'addresses.province',
                'products.name as product_name',
                'products.image as product_image',
                'products.category_id'
            )->orderBy('orders.created_at', 'desc')
            ->get();

        return view('Otssellerorderstab', compact('orders'));
    }

    // Update individual order piece status workflow parameters
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,shipped,delivered',
            'tracking_number' => 'nullable|string|max:255'
        ]);

        $sellerId = Auth::id() ?? 1;

        // Multi-tenant check layer protection
        $orderItem = DB::table('order_items')->where('id', $id)->where('seller_id', $sellerId)->first();
        if (!$orderItem) {
            return redirect()->back()->with('error', 'Item transactional record missing.');
        }

        // Update the item status
        DB::table('order_items')->where('id', $id)->update([
            'status' => $request->status,
            'updated_at' => now()
        ]);

        // If a tracking code accompanies the item shipment, record it on the parent order
        if ($request->filled('tracking_number')) {
            DB::table('orders')->where('id', $orderItem->order_id)->update([
                'tracking_number' => $request->tracking_number,
                'status' => $request->status == 'shipped' ? 'shipped' : 'confirmed',
                'updated_at' => now()
            ]);
        }

        return redirect()->back()->with('success', 'Order state updated, customer notified.');
    }
}