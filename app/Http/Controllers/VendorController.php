<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VendorController extends Controller
{
    //Form Page 1
    public function step1(Request $request){
        $vendorData = $request->session()->get('vendor_data', []);

        return view('vendor.step1', compact('vendorData'));
    }

    public function postStep1(Request $request){
        $validated = $request->validate([
            'store_name' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'required|string',
        ]);

        $vendorData = $request->session()->get('vendorData', []);
        $vendorData = array_merge($vendorData, $validated);
        $request->session()->put('vendor_data', $vendorData);

        return redirect()->route('vendor.step2');
    }

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
            'store_type' => 'required|string',
            'legal_name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string',
            'postal_code' => 'required|string',
            'support_email' => 'required|email',
            'id_upload' => 'nullable|file|mimes:pdf,jpg,png|max:5120', // 5MB Max
            'business_reg' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ]);

        $vendorData = $request->session()->get('vendor_data', []);

        // Handle ID Uploads
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
            'bank_name' => 'required|string',
            'bank_account_name' => 'required|string',
            'account_number' => 'required|string',
            'shipping_method' => 'required|array', // Assuming checkboxes return an array
        ]);

        $vendorData = $request->session()->get('vendor_data', []);
        $vendorData = array_merge($vendorData, $validated);
        $request->session()->put('vendor_data', $vendorData);

        return redirect()->route('vendor.step4');
    }

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
        // Require them to check the agreement box
        $request->validate([
            'agree_terms' => 'accepted'
        ]);

        // Grab all the accumulated data from the session
        $vendorData = $request->session()->get('vendor_data');

        // 1. Move temporary files to their permanent homes
        $finalLogoPath = null;
        if (isset($vendorData['store_logo_path'])) {
            $finalLogoPath = str_replace('tmp_vendor/', 'vendors/logos/', $vendorData['store_logo_path']);
            Storage::disk('public')->move($vendorData['store_logo_path'], $finalLogoPath);
        }

        $finalIdPath = null;
        if (isset($vendorData['id_upload_path'])) {
            $finalIdPath = str_replace('tmp_vendor/', 'vendors/documents/', $vendorData['id_upload_path']);
            Storage::disk('public')->move($vendorData['id_upload_path'], $finalIdPath);
        }

    }

    public function cancelApplication(Request $request){
        $request->session()->forget('vendor_data');

        return redirect('/home')->with('message', 'Application Cancelled');
    }
}
