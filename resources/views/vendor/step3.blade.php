@extends('common.main')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div class="d-flex mb-5 text-center px-4">
                <div class="flex-fill"><div class="step-indicator step-completed"><i class="bi bi-check"></i></div><small class="d-block mt-2 fw-bold">Store Profile</small></div>
                <div class="step-line" style="background-color: #198754;"></div>
                <div class="flex-fill"><div class="step-indicator step-completed"><i class="bi bi-check"></i></div><small class="d-block mt-2 fw-bold">Legal</small></div>
                <div class="step-line" style="background-color: #198754;"></div>
                <div class="flex-fill"><div class="step-indicator step-active">3</div><small class="d-block mt-2 fw-bold">Financial</small></div>
                <div class="step-line"></div>
                <div class="flex-fill"><div class="step-indicator step-pending">4</div><small class="d-block mt-2 fw-bold text-muted">Review</small></div>
            </div>

            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <a href="{{ route('vendor.cancel') }}" class="btn btn-sm btn-light border position-absolute text-muted" style="top: 20px; right: 20px; border-radius: 8px;">
                    <i class="bi bi-x-lg me-1"></i> Exit
                </a>
                <div class="card-body p-5">
                    
                    <form method="POST" action="{{ route('vendor.step3.post') }}">
                        @csrf
                        <h4 class="fw-bold mb-4" style="color: #a52a2a;">Financial & Shipping</h4>
                        
                        <div class="alert alert-info bg-light border-0 small">
                            <i class="bi bi-info-circle me-2"></i> This account will be used to send your sales payouts.
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Bank Name *</label>
                            <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $vendorData['bank_name'] ?? '') }}" required>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Bank Account Name *</label>
                                <input type="text" name="bank_account_name" class="form-control" value="{{ old('bank_account_name', $vendorData['bank_account_name'] ?? '') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Account Number *</label>
                                <input type="text" name="account_number" class="form-control" value="{{ old('account_number', $vendorData['account_number'] ?? '') }}" required>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="mb-4">
                            <label class="form-label fw-bold small">Preferred Shipping Method *</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="shipping_method[]" value="PowerPuff Courier" 
                                    {{ in_array('PowerPuff Courier', old('shipping_method', $vendorData['shipping_method'] ?? [])) ? 'checked' : '' }}>
                                <label class="form-check-label">PowerPuff Pets Courier (Recommended)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="shipping_method[]" value="Self-Shipping"
                                    {{ in_array('Self-Shipping', old('shipping_method', $vendorData['shipping_method'] ?? [])) ? 'checked' : '' }}>
                                <label class="form-check-label">Self-Shipping / Third-Party (Lalamove, Grab)</label>
                            </div>
                            @error('shipping_method') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('vendor.step2') }}" class="btn btn-light fw-bold px-4 rounded-pill border"><i class="bi bi-arrow-left me-2"></i> Back</a>
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
    .step-completed { background-color: #198754; color: white; }
    .step-pending { background-color: #e9ecef; color: #6c757d; }
    .step-line { height: 4px; background-color: #e9ecef; flex-grow: 1; margin-top: 17px; }
</style>