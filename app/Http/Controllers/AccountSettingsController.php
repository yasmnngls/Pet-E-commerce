<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountSettingsController extends Controller
{
    public function edit()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Fetch the default address for the view
        $defaultAddress = \App\Models\Address::where('user_id', $user->id)
            ->where('is_default', true)
            ->first();

        return view('profile.settings', compact('user', 'defaultAddress'));
    }

    public function update(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user instanceof User) {
            return redirect()->route('login');
        }

        $request->validate([
            'name'            => 'required|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'full_name'       => 'required|string|max:255',
            'phone'           => 'required|string|max:20',
            'street'          => 'required|string|max:255',
            'barangay'        => 'required|string|max:255',
            'city'            => 'required|string|max:100',
            'province'        => 'required|string|max:100',
        ]);

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/avatars'), $filename);
            $user->profile_picture = 'images/avatars/' . $filename;
        }

        $user->name = $request->name;
        $user->save();

        $address = \App\Models\Address::where('user_id', $user->id)
            ->where('is_default', true)
            ->first();

        if (!$address) {
            $address = new \App\Models\Address();
            $address->user_id = $user->id;
            $address->is_default = true;
        }

        $address->label     = 'Home';
        $address->full_name = $request->full_name;
        $address->phone     = $request->phone;
        $address->street    = $request->street;
        $address->barangay  = $request->barangay;
        $address->city      = $request->city;
        $address->province  = $request->province;
        $address->save();

        return redirect('Home')->with('success', 'Profile updated successfully!');
    }
}