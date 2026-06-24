<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class PageController extends Controller
{
    public function landing()
    {
        // Load real approved products for the Best Sellers section
        $bestSellers = Product::where('status', 'approved')
            ->with('seller')
            ->latest()
            ->take(8)
            ->get();

        // Load categories for the category strip
        $categories = Category::all();

        return view('landing', compact('bestSellers', 'categories'));
    }
}