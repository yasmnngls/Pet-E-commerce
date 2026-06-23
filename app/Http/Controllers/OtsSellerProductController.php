<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OtsSellerProductController extends Controller
{
    // Display all products belonging to the logged-in seller
    public function index()
    {
        // For testing/development, if Auth isn't set up yet, fallback to user ID 1 safely
        $sellerId = Auth::id() ?? 1; 

        // Fetch products and join categories table to get the text label
        $products = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('products.seller_id', $sellerId)
            ->select('products.*', 'categories.name as category_name')
            ->orderBy('products.created_at', 'desc')
            ->get();

        // Get all categories to populate the Add/Edit dropdown menus
        $categories = DB::table('categories')->get();

        return view('Otssellerproductstab', compact('products', 'categories'));
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

        // Handle File Upload to local public storage (or Supabase Bucket if configured)
        if ($request->hasFile('product_image')) {
            $imagePath = $request->file('product_image')->store('products', 'public');
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
        ]);

        $sellerId = Auth::id() ?? 1;

        // Verify ownership protection boundary
        $product = DB::table('products')->where('id', $id)->where('seller_id', $sellerId)->first();
        if (!$product) {
            return redirect()->back()->with('error', 'Unauthorized or product not found.');
        }

        DB::table('products')->where('id', $id)->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . $id,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'updated_at' => now()
        ]);

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