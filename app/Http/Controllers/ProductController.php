<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Show a single product detail page.
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('status', 'approved')
            ->with(['category', 'seller'])
            ->firstOrFail();

        // Related products: same category, excluding this one, up to 4
        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'approved')
            ->with('seller')
            ->take(4)
            ->get();

        return view('product', compact('product', 'related'));
    }
}