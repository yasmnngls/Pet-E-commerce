<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    /**
     * Form Page 1: Display basic store info
     */
    public function step1(Request $request)
    {
        $vendorData = $request->session()->get('vendor_data', []);
        return view('vendor.step1', compact('vendorData'));
    }

    public function postStep1(Request $request)
    {
        $validated = $request->validate([
            'store_name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string'],
            'description' => ['required', 'string'],
        ]);

        // FIXED TYPO: Unified to use 'vendor_data' consistently
        $vendorData = $request->session()->get('vendor_data', []);
        $vendorData = array_merge($vendorData, $validated);
        $request->session()->put('vendor_data', $vendorData);

        return redirect()->route('vendor.step2');
    }

    /**
     * Form Page 2: Legal Details & Verification Documents
     */
    public function step2(Request $request)
    {
        if (!$request->session()->has('vendor_data.store_name')) {
            return redirect()->route('vendor.step1');
        }
        $vendorData = $request->session()->get('vendor_data', []);
        return view('vendor.step2', compact('vendorData'));
    }

    public function postStep2(Request $request)
    {
        $validated = $request->validate([
            'store_type' => ['required', 'string'],
            'legal_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string'],
            'postal_code' => ['required', 'string'],
            'support_email' => ['required', 'email'],
            'id_upload' => ['nullable', 'file', 'mimes:pdf,jpg,png', 'max:5120'], // 5MB Upper Bound
            'business_reg' => ['nullable', 'file', 'mimes:pdf,jpg,png', 'max:5120'],
        ]);

        $vendorData = $request->session()->get('vendor_data', []);

        // Write file uploads securely into temporary public storage paths
        if ($request->hasFile('id_upload')) {
            $validated['id_upload_path'] = $request->file('id_upload')->store('tmp_vendor', 'public');
        }
        if ($request->hasFile('business_reg')) {
            $validated['business_reg_path'] = $request->file('business_reg')->store('tmp_vendor', 'public');
        }

        $vendorData = array_merge($vendorData, $validated);
        $request->session()->put('vendor_data', $vendorData);

        return redirect()->route('vendor.step3');
    }

    /**
     * Form Page 3: Payout Banking Setup & Logistics
     */
    public function step3(Request $request)
    {
        if (!$request->session()->has('vendor_data.legal_name')) {
            return redirect()->route('vendor.step2');
        }
        $vendorData = $request->session()->get('vendor_data', []);
        return view('vendor.step3', compact('vendorData'));
    }

    public function postStep3(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => ['required', 'string'],
            'bank_account_name' => ['required', 'string'],
            'account_number' => ['required', 'string'],
            'shipping_method' => ['required', 'array'], 
        ]);

        $vendorData = $request->session()->get('vendor_data', []);
        $vendorData = array_merge($vendorData, $validated);
        $request->session()->put('vendor_data', $vendorData);

        return redirect()->route('vendor.step4');
    }

    /**
     * Form Page 4: Terms Agreement & DB Compilation
     */
    public function step4(Request $request)
    {
        if (!$request->session()->has('vendor_data.bank_name')) {
            return redirect()->route('vendor.step3');
        }
        $vendorData = $request->session()->get('vendor_data', []);
        return view('vendor.step4', compact('vendorData'));
    }

    public function postStep4(Request $request)
    {
        $request->validate([
            'agree_terms' => ['accepted']
        ]);

        $vendorData = $request->session()->get('vendor_data');
        $userId = Auth::id() ?? 1; // Fallback security check during local iterations

        // 1. Move verification documents out of temporary staging paths
        $finalIdPath = null;
        if (isset($vendorData['id_upload_path'])) {
            $finalIdPath = str_replace('tmp_vendor/', 'vendors/documents/', $vendorData['id_upload_path']);
            Storage::disk('public')->move($vendorData['id_upload_path'], $finalIdPath);
        }

        $finalBusinessRegPath = null;
        if (isset($vendorData['business_reg_path'])) {
            $finalBusinessRegPath = str_replace('tmp_vendor/', 'vendors/documents/', $vendorData['business_reg_path']);
            Storage::disk('public')->move($vendorData['business_reg_path'], $finalBusinessRegPath);
        }

        // 2. Format localized text parts to line up with your exact database migration columns
        $fullAddress = $vendorData['address'] . ', ' . $vendorData['city'] . ' ' . $vendorData['postal_code'];

        // 3. Commit data rows straight to your migration schema
        DB::table('seller_applications')->insert([
            'user_id' => $userId,
            'store_name' => $vendorData['store_name'],
            'store_type' => $vendorData['store_type'],
            'legal_name' => $vendorData['legal_name'],
            'id_upload_path' => $finalIdPath,
            'business_registration_path' => $finalBusinessRegPath,
            'store_description' => $vendorData['description'],
            'business_address' => $fullAddress,
            'customer_support_contact' => $vendorData['support_email'],
            'bank_name' => $vendorData['bank_name'],
            'bank_account_number' => $vendorData['account_number'],
            'bank_account_holder' => $vendorData['bank_account_name'],
            'product_categories' => json_encode([$vendorData['category']]), // Preserves your schema JSON type arrays
            'shipping_methods' => json_encode($vendorData['shipping_method']),
            'terms_accepted' => true,
            'status' => 'pending', // Flags the application for Admin Dashboard visibility!
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 4. Wipe session memory completely to prepare for clean subsequent interactions
        $request->session()->forget('vendor_data');

        return redirect('/Home')->with('success', 'Application submitted! Our system administrators will audit your shop profile parameters shortly.');
    }

    /**
     * Clear application progress memory
     */
    public function cancelApplication(Request $request)
    {
        $request->session()->forget('vendor_data');
        return redirect('/Home')->with('message', 'Vendor registration sequence aborted.');
    }
}