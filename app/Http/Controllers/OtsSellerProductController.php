<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OtsSellerProductController extends Controller
{
    // Display all products belonging to the logged-in seller
    public function index()
    {
        $products = Product::where('seller_id', Auth::id())
            ->with('category')
            ->latest()
            ->get()
            ->map(function ($product) {
                $product->category_name = $product->category->name ?? 'General';
                return $product;
            });

        $categories = Category::all();

        return view('Otssellerproductstab', compact('products', 'categories'));
    }
    // You also need to secure your edit/update/delete routes!
    public function edit($id)
    {
        // use firstOrFail() to ensure they can't edit someone else's product by guessing the ID
        $product = Product::where('id', $id)
                          ->where('seller_id', Auth::id())
                          ->firstOrFail();

        return view('seller.products.edit', compact('product'));
    }
    // Add a new product to Supabase
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|integer',
            'stock_quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $sellerId = Auth::id() ?? 1;
        $imagePath = null;

        // Store uploads in the Laravel public disk so they resolve through /storage/...
        if ($request->hasFile('product_image')) {
            $storedPath = $request->file('product_image')->store('products', 'public');
            $imagePath = 'storage/' . $storedPath;
        }
        DB::table('products')->insert([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . time(), // Enforces unique constraint safety
            'category_id' => $request->category_id,
            'seller_id' => $sellerId,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'image' => $imagePath,
            'status' => 'active', // Set active automatically for trusted sellers
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Product added successfully to your catalog!');
    }

    // Update an existing item inside Supabase
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|integer',
            'stock_quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $sellerId = Auth::id();

        if (!$sellerId) {
            return redirect()->back()->with('error', 'You must be logged in.');
        }

        // Verify ownership protection boundary
        $product = DB::table('products')->where('id', $id)->where('seller_id', $sellerId)->first();
        if (!$product) {
            return redirect()->back()->with('error', 'Unauthorized or product not found.');
        }

        $updateData = [
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . $id,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'updated_at' => now()
        ];

        if ($request->hasFile('product_image')) {
            $storedPath = $request->file('product_image')->store('products', 'public');
            $updateData['image'] = 'storage/' . $storedPath;
        }

        DB::table('products')->where('id', $id)->update($updateData);

        return redirect()->back()->with('success', 'Product specifications updated successfully!');
    }

    // Delete a product listing
    public function destroy($id)
    {
        $sellerId = Auth::id() ?? 1;
        
        DB::table('products')->where('id', $id)->where('seller_id', $sellerId)->delete();

        return redirect()->back()->with('success', 'Listing deleted cleanly from database.');
    }
}