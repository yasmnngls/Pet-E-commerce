<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Banner; // <-- Safely imported the model

class PageController extends Controller
{
    public function landing()
    {
        // 1. Keep your real approved products for the Best Sellers section
        $bestSellers = Product::where('status', 'approved')
            ->with('seller')
            ->latest()
            ->take(8)
            ->get();

        // 2. Keep your categories for the category strip
        $categories = Category::all();

        // 3. New: Fetch banners and key them by their 'slot' values ('main_large', 'side_top', etc.)
        $banners = Banner::all()->keyBy('slot');

        // 4. Safely return everything to your view via compact
        return view('landing', compact('bestSellers', 'categories', 'banners'));
    }
}