@extends('common.main1')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div class="d-flex mb-5 text-center px-4">
                <div class="flex-fill"><div class="step-indicator step-completed"><i class="bi bi-check"></i></div><small class="d-block mt-2 fw-bold">Store Profile</small></div>
                <div class="step-line" style="background-color: #198754;"></div>
                <div class="flex-fill"><div class="step-indicator step-active">2</div><small class="d-block mt-2 fw-bold">Legal</small></div>
                <div class="step-line"></div>
                <div class="flex-fill"><div class="step-indicator step-pending">3</div><small class="d-block mt-2 fw-bold text-muted">Financial</small></div>
                <div class="step-line"></div>
                <div class="flex-fill"><div class="step-indicator step-pending">4</div><small class="d-block mt-2 fw-bold text-muted">Review</small></div>
            </div>

            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <a href="{{ route('vendor.cancel') }}" class="btn btn-sm btn-light border position-absolute text-muted" style="top: 20px; right: 20px; border-radius: 8px;">
                    <i class="bi bi-x-lg me-1"></i> Exit
                </a>
                <div class="card-body p-5">
                    
                    <form method="POST" action="{{ route('vendor.step2.post') }}" enctype="multipart/form-data">
                        @csrf
                        <h4 class="fw-bold mb-4" style="color: #a52a2a;">Legal & Contact Information</h4>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Store Type *</label>
                            <select name="store_type" class="form-select" required>
                                <option {{ old('store_type', $vendorData['store_type'] ?? '') == 'Individual / Sole Proprietor' ? 'selected' : '' }}>Individual / Sole Proprietor</option>
                                <option {{ old('store_type', $vendorData['store_type'] ?? '') == 'Registered Business / Corporation' ? 'selected' : '' }}>Registered Business / Corporation</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Legal Name (Owner or Company) *</label>
                            <input type="text" name="legal_name" class="form-control" value="{{ old('legal_name', $vendorData['legal_name'] ?? '') }}" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Valid ID Upload *</label>
                                <input class="form-control" type="file" name="id_upload" accept=".jpg,.png,.pdf" {{ empty($vendorData['id_upload_path']) ? 'required' : '' }}>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Business Reg / TIN</label>
                                <input class="form-control" type="file" name="business_reg" accept=".jpg,.png,.pdf">
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Business / Pickup Address *</label>
                            <input type="text" name="address" class="form-control mb-2" placeholder="Street Address" value="{{ old('address', $vendorData['address'] ?? '') }}" required>
                            <div class="row">
                                <div class="col-6"><input type="text" name="city" class="form-control" placeholder="City" value="{{ old('city', $vendorData['city'] ?? '') }}" required></div>
                                <div class="col-6"><input type="text" name="postal_code" class="form-control" placeholder="Postal Code" value="{{ old('postal_code', $vendorData['postal_code'] ?? '') }}" required></div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small">Customer Support Email *</label>
                            <input type="email" name="support_email" class="form-control" value="{{ old('support_email', $vendorData['support_email'] ?? '') }}" required>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('vendor.step1') }}" class="btn btn-light fw-bold px-4 rounded-pill border"><i class="bi bi-arrow-left me-2"></i> Back</a>
                            <button type="submit" class="btn text-white fw-bold px-4 rounded-pill" style="background-color: #a52a2a;">Next Step <i class="bi bi-arrow-right ms-2"></i></button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    body { background-color: #F5EFE6; }
    .step-indicator { width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; margin: 0 auto; }
    .step-active { background-color: #a52a2a; color: white; }
    .step-completed { background-color: #198754; color: white; } /* Green for completed */
    .step-pending { background-color: #e9ecef; color: #6c757d; }
    .step-line { height: 4px; background-color: #e9ecef; flex-grow: 1; margin-top: 17px; }
</style>