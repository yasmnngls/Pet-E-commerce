@extends('common.main')
@section('title', 'Account Settings')
@section('content')

<div class="container py-5" style="max-width: 900px;">
    <div class="card border-0 shadow-sm rounded-4 p-4">
        <h3 class="fw-bold mb-4">Account Settings</h3>

        @if(session('success'))
            <div class="alert alert-success rounded-3">{{ session('success') }}</div>
        @endif

        <form action="{{ route('account.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-4">
                <div class="col-md-4 text-center">
                    <div class="mb-3">
                        <img src="{{ Auth::user()->profile_picture ? asset(Auth::user()->profile_picture) : asset('images/pet3.jpg') }}"
                             alt="Profile Picture"
                             class="rounded-circle border shadow-sm"
                             style="width: 140px; height: 140px; object-fit: cover;">
                    </div>
                    <input type="file" name="profile_picture" class="form-control form-control-sm">
                </div>

                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', Auth::user()->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Full Name</label>
                        <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $defaultAddress->full_name ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $defaultAddress->phone ?? '') }}" required>
                    </div>
                </div>
            </div>

            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Street</label>
                    <input type="text" name="street" class="form-control" value="{{ old('street', $defaultAddress->street ?? '') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Barangay</label>
                    <input type="text" name="barangay" class="form-control" value="{{ old('barangay', $defaultAddress->barangay ?? '') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">City</label>
                    <input type="text" name="city" class="form-control" value="{{ old('city', $defaultAddress->city ?? '') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Province</label>
                    <input type="text" name="province" class="form-control" value="{{ old('province', $defaultAddress->province ?? '') }}" required>
                </div>
            </div>

            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-dark rounded-pill px-4">Save Changes</button>
            </div>
        </form>
    </div>
</div>

@endsection
