@extends('Otssellertabslayout')

@section('content')
<div class="custom-card p-4">
    <h3 class="mb-3">Edit Store Profile</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('seller.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-4 text-center">
                <img src="{{ $store?->logo_url ?? asset('images/default-store.png') }}" alt="Store Logo" class="rounded mb-3" style="width:200px;height:200px;object-fit:cover;">
                <div class="mb-2">
                    <label class="btn btn-outline-secondary">Change Logo<input type="file" name="logo" class="d-none"></label>
                </div>
            </div>
            <div class="col-md-8">
                <div class="mb-3">
                    <label class="form-label">Store Name</label>
                    <input type="text" name="store_name" class="form-control" value="{{ old('store_name', $store->store_name ?? '') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="store_description" class="form-control" rows="4">{{ old('store_description', $store->store_description ?? '') }}</textarea>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-ppp-red" type="submit">Save</button>
                    <a href="{{ route('seller.products') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
