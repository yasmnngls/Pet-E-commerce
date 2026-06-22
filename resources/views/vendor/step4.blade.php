@extends('common.main')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div class="d-flex mb-5 text-center px-4">
                <div class="flex-fill"><div class="step-indicator step-completed"><i class="bi bi-check"></i></div></div>
                <div class="step-line" style="background-color: #198754;"></div>
                <div class="flex-fill"><div class="step-indicator step-completed"><i class="bi bi-check"></i></div></div>
                <div class="step-line" style="background-color: #198754;"></div>
                <div class="flex-fill"><div class="step-indicator step-completed"><i class="bi bi-check"></i></div></div>
                <div class="step-line" style="background-color: #198754;"></div>
                <div class="flex-fill"><div class="step-indicator step-active">4</div><small class="d-block mt-2 fw-bold">Review</small></div>
            </div>

            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <a href="{{ route('vendor.cancel') }}" class="btn btn-sm btn-light border position-absolute text-muted" style="top: 20px; right: 20px; border-radius: 8px;">
                    <i class="bi bi-x-lg me-1"></i> Exit
                </a>
                <div class="card-body p-5">
                    
                    <form method="POST" action="{{ route('vendor.step4.post') }}">
                        @csrf
                        <h4 class="fw-bold mb-4" style="color: #a52a2a;">Review & Submit</h4>
                        
                        <div class="card bg-light border-0 mb-4 p-3">
                            <h6 class="fw-bold">Application Summary:</h6>
                            <ul class="mb-0 small">
                                <li><strong>Store:</strong> {{ $vendorData['store_name'] ?? '' }}</li>
                                <li><strong>Category:</strong> {{ $vendorData['category'] ?? '' }}</li>
                                <li><strong>Entity:</strong> {{ $vendorData['legal_name'] ?? '' }} ({{ $vendorData['store_type'] ?? '' }})</li>
                                <li><strong>Bank Setup:</strong> {{ $vendorData['bank_name'] ?? '' }}</li>
                            </ul>
                        </div>

                        <div class="card border-0 mb-4">
                            <div class="card-body border rounded" style="height: 150px; overflow-y: scroll; font-size: 0.85rem; background-color: #fff;">
                                <strong>PowerPuff Pets Vendor Agreement</strong><br><br>
                                1. You agree to fulfill orders within 48 hours of purchase.<br>
                                2. PowerPuff Pets takes a standard 5% commission on all successful sales.<br>
                                3. You are responsible for ensuring all pet products meet local health and safety regulations.<br>
                                4. Fraudulent listings will result in immediate account termination.
                            </div>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="agree_terms" required id="agreeCheck">
                            <label class="form-check-label fw-bold small" for="agreeCheck">
                                I have read and agree to the PowerPuff Pets Vendor Terms of Service.
                            </label>
                            @error('agree_terms') <span class="d-block text-danger small mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('vendor.step3') }}" class="btn btn-light fw-bold px-4 rounded-pill border"><i class="bi bi-arrow-left me-2"></i> Back</a>
                            <button type="submit" class="btn text-white fw-bold px-4 rounded-pill shadow-sm" style="background-color: #198754;"><i class="bi bi-check-circle me-2"></i> Submit Application</button>
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
    .step-line { height: 4px; background-color: #e9ecef; flex-grow: 1; margin-top: 17px; }
</style>