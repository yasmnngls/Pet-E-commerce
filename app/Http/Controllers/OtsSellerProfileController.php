<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\SellerApplication;

class OtsSellerProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $store = $user->sellerApplication ?? null;
        return view('Otssellerprofile', compact('store'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $store = SellerApplication::firstOrCreate(['user_id' => $user->id]);

        $data = $request->validate([
            'store_name' => 'required|string|max:255',
            'store_description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        if ($request->hasFile('logo')) {
            $stored = $request->file('logo')->store('stores', 'public');
            $data['logo_path'] = $stored;
        }

        $store->update([
            'store_name' => $data['store_name'],
            'store_description' => $data['store_description'] ?? $store->store_description,
            'logo_path' => $data['logo_path'] ?? $store->logo_path,
        ]);

        return redirect()->route('seller.products')->with('success', 'Store profile updated.');
    }
}
