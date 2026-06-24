@extends('common.main1')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div class="text-center mb-5">
                <h2 class="fw-bold" style="color: #a52a2a;">Partner with PowerPuff Pets</h2>
                <p class="text-muted">Complete your vendor application to start selling.</p>
            </div>

            <div class="d-flex mb-5 text-center px-4">
                <div class="flex-fill"><div class="step-indicator step-active">1</div><small class="d-block mt-2 fw-bold">Store Profile</small></div>
                <div class="step-line"></div>
                <div class="flex-fill"><div class="step-indicator step-pending">2</div><small class="d-block mt-2 fw-bold text-muted">Legal</small></div>
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
                    
                    <form method="POST" action="{{ route('vendor.step1.post') }}" enctype="multipart/form-data">
                        @csrf
                        <h4 class="fw-bold mb-4" style="color: #a52a2a;">Store Branding & Products</h4>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Store Name *</label>
                            <input type="text" name="store_name" class="form-control" value="{{ old('store_name', $vendorData['store_name'] ?? '') }}" required>
                            @error('store_name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Main Product Category *</label>
                            <select name="category" class="form-select" required>
                                <option disabled {{ empty($vendorData['category']) ? 'selected' : '' }}>Select a category...</option>
                                <option {{ old('category', $vendorData['category'] ?? '') == 'Pet Food & Treats' ? 'selected' : '' }}>Pet Food & Treats</option>
                                <option {{ old('category', $vendorData['category'] ?? '') == 'Toys & Accessories' ? 'selected' : '' }}>Toys & Accessories</option>
                                <option {{ old('category', $vendorData['category'] ?? '') == 'Grooming & Health' ? 'selected' : '' }}>Grooming & Health</option>
                            </select>
                            @error('category') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Store Logo *</label>
                            <input class="form-control" type="file" name="store_logo" accept="image/*" {{ empty($vendorData['store_logo_path']) ? 'required' : '' }}>
                            @if(!empty($vendorData['store_logo_path']))
                                <small class="text-success"><i class="bi bi-check-circle"></i> Logo uploaded. Choose a new file to replace.</small>
                            @endif
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small">Store Description *</label>
                            <textarea name="description" class="form-control" rows="3" required>{{ old('description', $vendorData['description'] ?? '') }}</textarea>
                            @error('description') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="d-flex justify-content-end">
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
    .step-pending { background-color: #e9ecef; color: #6c757d; }
    .step-line { height: 4px; background-color: #e9ecef; flex-grow: 1; margin-top: 17px; }
</style>