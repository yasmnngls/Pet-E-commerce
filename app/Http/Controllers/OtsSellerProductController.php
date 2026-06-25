<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Services\SupabaseStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OtsSellerProductController extends Controller
{
    public function index(Request $request)
    {
        $selectedPetCategory = $request->input('pet_category');
        $searchQuery = $request->input('q');

        $products = Product::where('seller_id', Auth::id())
            ->with('category')
            ->when($selectedPetCategory, fn($q) => $q->where('category_id', $selectedPetCategory))
            ->when($searchQuery, fn($q) => $q->where(function ($q) use ($searchQuery) {
                $q->where('name', 'like', "%{$searchQuery}%")
                  ->orWhere('description', 'like', "%{$searchQuery}%");
            }))
            ->latest()
            ->get()
            ->map(function ($product) {
                $petName = $product->category->name ?? 'General';
                $productTypeName = $product->product_category ?? null;
                $product->category_name = $productTypeName ? $petName . ' / ' . $productTypeName : $petName;
                return $product;
            });

        $categories = Category::all();
        $petCategories = Category::where('type', 'pet')->get();
        $productCategories = Category::where('type', 'product')->get();
        $store = Auth::user()->sellerApplication ?? null;

        return view('Otssellerproductstab', compact('products', 'categories', 'petCategories', 'productCategories', 'selectedPetCategory', 'searchQuery', 'store'));
    }

    public function edit($id)
    {
        $product = Product::where('id', $id)
                          ->where('seller_id', Auth::id())
                          ->firstOrFail();

        return view('seller.products.edit', compact('product'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $imagePath = null;
        if ($request->hasFile('product_image')) {
            $storage = new SupabaseStorageService();
            $imagePath = $storage->upload($request->file('product_image'), 'products');
        }

        DB::table('products')->insert([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . time(),
            'category_id' => $request->category_id,
            'product_category' => $request->product_category,
            'seller_id' => Auth::id() ?? 1,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'image' => $imagePath,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Product uploaded to cloud successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $updateData = [
            'name' => $request->name,
            'category_id' => $request->category_id,
            'product_category' => $request->product_category,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'updated_at' => now()
        ];
        
        if ($request->hasFile('product_image')) {
            $storage = new SupabaseStorageService();
            $updateData['image'] = $storage->upload($request->file('product_image'), 'products');
        }

        DB::table('products')->where('id', $id)->update($updateData);
        return redirect()->back()->with('success', 'Product updated in cloud!');
    }

    public function destroy($id)
    {
        $sellerId = Auth::id() ?? 1;
        DB::table('products')->where('id', $id)->where('seller_id', $sellerId)->delete();
        return redirect()->back()->with('success', 'Listing deleted.');
    }
}