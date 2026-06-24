<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AccountSettingsController extends Controller
{
    public function edit()
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user instanceof User) {
            return redirect()->route('login');
        }

        $defaultAddress = $user->addresses()->where('is_default', true)->first();

        return view('profile.settings', compact('user', 'defaultAddress'));
    }

    public function update(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user instanceof User) {
            return redirect()->route('login');
        }

        // Validate to match your existing address schema definitions
        $request->validate([
            'name' => 'required|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'street' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
        ]);

        // Handle Avatar Profile Picture Update
        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete(str_replace('storage/', '', $user->profile_picture));
            }
            $path = $request->file('profile_picture')->store('avatars', 'public');
            $user->profile_picture = 'storage/' . $path;
        }

        // Update Username
        $user->name = $request->name;
        $user->save();

        // 1. Manually find if this specific user already has a default address record
        $address = \App\Models\Address::where('user_id', $user->id)
            ->where('is_default', true)
            ->first();

        // 2. If it doesn't exist, instantiate a fresh model instance
        if (!$address) {
            $address = new \App\Models\Address();
            $address->user_id = $user->id;
            $address->is_default = true;
        }

        // 3. Explicitly map your form inputs to the model attributes
        $address->label = 'Home';
        $address->full_name = $request->full_name;
        $address->phone = $request->phone;
        $address->street = $request->street;
        $address->barangay = $request->barangay;
        $address->city = $request->city;
        $address->province = $request->province;

        // 4. Persist to Postgres safely
        $address->save();

        return redirect('Home');
    }
}