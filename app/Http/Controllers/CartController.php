<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;

class CartController extends Controller
{
    /**
     * Get or create the cart for the logged-in user.
     */
    private function getOrCreateCart()
    {
        return Cart::firstOrCreate(['user_id' => Auth::id()]);
    }

    /**
     * Show the cart page.
     */
    public function index()
    {
        $cart = Cart::where('user_id', Auth::id())->first();
        $items = collect();

        if ($cart) {
            $items = CartItem::where('cart_id', $cart->id)
                ->with('item') // polymorphic: resolves to Product
                ->get()
                ->filter(fn($ci) => $ci->item !== null); // drop orphaned rows
        }

        $subtotal = $items->sum(fn($ci) => $ci->item->price * $ci->quantity);

        return view('cart', compact('items', 'subtotal'));
    }

    /**
     * Add a product to the cart (or increment qty if already there).
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity'   => 'integer|min:1',
        ]);

        $product  = Product::findOrFail($request->product_id);
        $qty      = (int) ($request->quantity ?? 1);
        $cart     = $this->getOrCreateCart();

        // Prevent adding if requested quantity exceeds available stock
        if ($product->stock_quantity < $qty) {
            return redirect()->back()->withErrors(['error' => "Only {$product->stock_quantity} left in stock for {$product->name}."]);
        }

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('item_type', Product::class)
            ->where('item_id', $product->id)
            ->first();

        if ($cartItem) {
            // Check if the NEW total quantity exceeds stock
            $newQty = $cartItem->quantity + $qty;
            if ($product->stock_quantity < $newQty) {
                return redirect()->back()->withErrors(['error' => "Cannot add more. You already have {$cartItem->quantity} in your cart, and only {$product->stock_quantity} are available."]);
            }
            $cartItem->increment('quantity', $qty);
        } else {
            CartItem::create([
                'cart_id'   => $cart->id,
                'item_type' => Product::class,
                'item_id'   => $product->id,
                'quantity'  => $qty,
            ]);
        }

        return redirect()->back()->with('success', "{$product->name} added to cart!");
    }

    /**
     * Update quantity of a cart item.
     */
    public function update(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $cart = Cart::where('user_id', Auth::id())->firstOrFail();
        $item = CartItem::where('id', $id)->where('cart_id', $cart->id)->firstOrFail();
        $item->update(['quantity' => $request->quantity]);

        return redirect()->route('cart.index')->with('success', 'Cart updated.');
    }

    /**
     * Remove a single item from the cart.
     */
    public function remove($id)
    {
        $cart = Cart::where('user_id', Auth::id())->firstOrFail();
        CartItem::where('id', $id)->where('cart_id', $cart->id)->delete();

        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }

    /**
     * Return the cart item count (used by the header badge).
     */
    public static function cartCount(): int
    {
        if (!Auth::check()) return 0;
        $cart = Cart::where('user_id', Auth::id())->first();
        if (!$cart) return 0;
        return CartItem::where('cart_id', $cart->id)->sum('quantity');
    }
}