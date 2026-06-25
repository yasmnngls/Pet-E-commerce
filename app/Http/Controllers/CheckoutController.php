<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\Product;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Cart::where('user_id', Auth::id())->first();

        if (!$cart) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $items = CartItem::where('cart_id', $cart->id)
            ->with('item')
            ->get()
            ->filter(fn($ci) => $ci->item !== null);

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal       = $items->sum(fn($ci) => $ci->item->price * $ci->quantity);
        $savedAddresses = Address::where('user_id', Auth::id())->get();

        return view('checkout', compact('items', 'subtotal', 'savedAddresses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'address_option' => 'required|string',
            'payment_method' => 'required|in:cod,gcash,card',
            'full_name' => 'required_if:address_option,new|nullable|string|max:255',
            'phone'     => 'required_if:address_option,new|nullable|string|max:20',
            'street'    => 'required_if:address_option,new|nullable|string|max:255',
            'barangay'  => 'required_if:address_option,new|nullable|string|max:255',
            'city'      => 'required_if:address_option,new|nullable|string|max:255',
            'province'  => 'required_if:address_option,new|nullable|string|max:255',
        ]);
        // On validation failure Laravel automatically redirects back to checkout.index
        // (the previous GET route) with errors and old input — index() then re-sets
        // all variables including $savedAddresses before the blade renders.

        $userId = Auth::id();

        // Resolve address
        if ($request->address_option === 'new') {
            $address = Address::create(array_merge(
                $request->only(['full_name', 'phone', 'street', 'barangay', 'city', 'province']),
                ['user_id' => $userId, 'label' => 'Home', 'is_default' => false]
            ));
        } else {
            $addressId = (int) str_replace('saved_', '', $request->address_option);
            $address   = Address::where('id', $addressId)->where('user_id', $userId)->firstOrFail();
        }

        // Load cart
        $cart      = Cart::where('user_id', $userId)->firstOrFail();
        $cartItems = CartItem::where('cart_id', $cart->id)
            ->with('item')
            ->get()
            ->filter(fn($ci) => $ci->item !== null);

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $total = $cartItems->sum(fn($ci) => $ci->item->price * $ci->quantity);

        try {
            $order = DB::transaction(function () use ($userId, $address, $cartItems, $total, $request, $cart) {

                $order = Order::create([
                    'order_number'   => 'ORD-' . strtoupper(Str::random(8)),
                    'user_id'        => $userId,
                    'address_id'     => $address->id,
                    'total'          => $total,
                    'status'         => 'pending',
                    'payment_method' => $request->payment_method,
                ]);

                foreach ($cartItems as $ci) {
                    $product = Product::where('id', $ci->item_id)->lockForUpdate()->first();

                    if ($product->stock_quantity < $ci->quantity) {
                        throw new \Exception("Sorry, \"{$product->name}\" just went out of stock.");
                    }

                    OrderItem::create([
                        'order_id'  => $order->id,
                        'item_type' => Product::class,
                        'item_id'   => $product->id,
                        'price'     => $product->price,
                        'quantity'  => $ci->quantity,
                        'seller_id' => $product->seller_id,
                        'status'    => 'pending',
                    ]);

                    $product->decrement('stock_quantity', $ci->quantity);
                }

                CartItem::where('cart_id', $cart->id)->delete();

                return $order;
            });

            return redirect()->route('checkout.confirmation', $order->id);

        } catch (\Exception $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }
    }

    public function confirmation($id)
    {
        $order = Order::with(['items.item', 'address'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('checkout', compact('order'));
    }
}