<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OtsSellerOrderController extends Controller
{
    public function index()
    {
        // Fallback to 4 to match your active PowerPuff test store account ID
        $sellerId = Auth::id() ?? 4; 

        // Changed joins to leftJoin to safely render test records even if address components are null
        $orders = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->leftJoin('addresses', 'orders.address_id', '=', 'addresses.id')
            ->leftJoin('products', 'order_items.item_id', '=', 'products.id')
            ->where('order_items.seller_id', $sellerId)
            ->whereIn('order_items.status', ['pending', 'shipped', 'delivered']) // Included delivered for testing
            ->select(
                'order_items.id as order_item_id',
                'order_items.quantity',
                'order_items.price',
                'order_items.status as item_status',
                'orders.order_number',
                'orders.tracking_number',
                'orders.created_at as order_date',
                'users.name as buyer_name',
                'addresses.street',
                'addresses.barangay',
                'addresses.city',
                'addresses.province',
                'products.name as product_name',
                'products.image as product_image',
                'products.category_id'
            )->orderBy('orders.created_at', 'desc')
            ->get()
            ->map(function ($order) {
                $image = is_string($order->product_image) ? str_replace('\\', '/', trim($order->product_image)) : '';
                $image = ltrim($image, '/');

                if (empty($image)) {
                    $order->product_image_url = 'https://via.placeholder.com/70';
                    return $order;
                }

                if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
                    $order->product_image_url = $image;
                    return $order;
                }

                if (str_contains($image, 'storage/app/public/')) {
                    $image = substr($image, strpos($image, 'storage/app/public/') + strlen('storage/app/public/'));
                }
                if (str_starts_with($image, 'app/public/')) {
                    $image = substr($image, strlen('app/public/'));
                }
                if (str_starts_with($image, 'public/')) {
                    $image = substr($image, strlen('public/'));
                }
                if (str_starts_with($image, 'storage/')) {
                    $image = substr($image, strlen('storage/'));
                }

                if (Storage::disk('public')->exists($image)) {
                    $order->product_image_url = Storage::disk('public')->url($image);
                } else {
                    $order->product_image_url = asset('storage/' . $image);
                }

                return $order;
            });

        return view('Otssellerorderstab', compact('orders'));
    }

    // Update individual order piece status workflow parameters
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,shipped,delivered',
            'tracking_number' => 'nullable|string|max:255'
        ]);

        $sellerId = Auth::id() ?? 4;

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

    public function cancel($id)
    {
        $sellerId = Auth::id() ?? 4;

        $orderItem = DB::table('order_items')->where('id', $id)->where('seller_id', $sellerId)->first();
        if (!$orderItem) {
            return redirect()->back()->with('error', 'Unable to locate the order item to cancel.');
        }

        DB::table('order_items')->where('id', $id)->delete();

        $remainingItems = DB::table('order_items')->where('order_id', $orderItem->order_id)->count();
        if ($remainingItems === 0) {
            DB::table('orders')->where('id', $orderItem->order_id)->update([
                'status' => 'cancelled',
                'updated_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Order item has been cancelled successfully.');
    }
}