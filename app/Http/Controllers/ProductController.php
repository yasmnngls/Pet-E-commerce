<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->whereIn('status', ['approved', 'active'])
            ->with(['category', 'seller'])
            ->firstOrFail();

        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->whereIn('status', ['approved', 'active'])
            ->with('seller')
            ->take(4)
            ->get();

        return view('product', compact('product', 'related'));
    }

    /**
     * Display all products belonging to the logged-in seller
     */
    public function index()
    {
        $sellerId = Auth::id() ?? 4;

        $products = DB::table('products')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->where('products.seller_id', $sellerId)
            ->select('products.*', 'categories.name as category_name')
            ->orderBy('products.created_at', 'desc')
            ->get();

        $categories = DB::table('categories')->get();

        return view('Otssellerproductstab', compact('products', 'categories'));
    }

    /**
     * Add a new product to your catalog
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'category_id'    => 'required|integer',
            'stock_quantity' => 'required|integer|min:0',
            'price'          => 'required|numeric|min:0',
            'product_image'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $sellerId  = Auth::id() ?? 4;
        $imagePath = null;

        if ($request->hasFile('product_image')) {
            $storedPath = $request->file('product_image')->store('products', 'public');
            $imagePath  = 'storage/' . $storedPath;
        }

        DB::table('products')->insert([
            'name'           => $request->name,
            'slug'           => Str::slug($request->name) . '-' . time(),
            'category_id'    => $request->category_id,
            'seller_id'      => $sellerId,
            'description'    => $request->description,
            'price'          => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'image'          => $imagePath,
            'status'         => 'pending',
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return redirect()->back()->with('success', 'Product submitted for admin approval!');
    }

    /**
     * Update an existing product listing
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'category_id'    => 'required|integer',
            'stock_quantity' => 'required|integer|min:0',
            'price'          => 'required|numeric|min:0',
            'product_image'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $sellerId = Auth::id() ?? 4;
        $product  = DB::table('products')->where('id', $id)->where('seller_id', $sellerId)->first();

        if (!$product) {
            return redirect()->back()->with('error', 'Unauthorized or product not found.');
        }

        $updateData = [
            'name'           => $request->name,
            'slug'           => Str::slug($request->name) . '-' . $id,
            'category_id'    => $request->category_id,
            'description'    => $request->description,
            'price'          => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'status'         => 'pending',
            'updated_at'     => now(),
        ];

        if ($request->hasFile('product_image')) {
            $storedPath          = $request->file('product_image')->store('products', 'public');
            $updateData['image'] = 'storage/' . $storedPath;
        }

        DB::table('products')->where('id', $id)->update($updateData);

        return redirect()->back()->with('success', 'Product updated and resubmitted for approval.');
    }

    /**
     * Delete a product listing
     */
    public function destroy($id)
    {
        $sellerId = Auth::id() ?? 4;
        DB::table('products')->where('id', $id)->where('seller_id', $sellerId)->delete();
        return redirect()->back()->with('success', 'Listing deleted cleanly from database.');
    }

    public function catalog(Request $request)
    {
        $query   = $request->input('q');            // search bar (?q=food)
        $catName = $request->input('category');     // category name (?category=Food)
        $petType = $request->input('pet_type');     // pet type filter
        $petCat  = $request->input('pet_category'); // pet category id
        $prodCat = $request->input('product_category'); // product category id

        $products = Product::whereIn('status', ['approved', 'active'])
            ->with(['seller', 'category'])
            ->when($query, fn($q) => $q->where(function ($q) use ($query) {
                $q->where('name', 'ilike', "%{$query}%")
                  ->orWhere('description', 'ilike', "%{$query}%");
            }))
            ->when($catName, fn($q) => $q->whereHas('category', fn($q) =>
                $q->where('name', 'ilike', "%{$catName}%")
            ))
            ->when($petType, fn($q) => $q->whereHas('category', fn($q) =>
                $q->where('name', 'ilike', "%{$petType}%")
            ))
            ->when($petCat,  fn($q) => $q->where('category_id', $petCat))
            ->when($prodCat, fn($q) => $q->where('product_category', $prodCat))
            ->latest()
            ->paginate(16)
            ->withQueryString();

        $petCategories     = Category::where('type', 'pet')->get();
        $productCategories = Category::where('type', 'product')->get();

        return view('catalog', compact(
            'products', 'petCategories', 'productCategories',
            'query', 'catName', 'petType', 'petCat', 'prodCat'
        ));
    }
}