@extends('common.main2')
@section('title', 'Admin Command Center')
@section('content')

<div class="container-fluid p-0 min-vh-100" style="background-color: #f1f2f6;">
    
    <div class="p-4 text-white d-flex justify-content-between align-items-center shadow-sm" style="background-color: #2f3542;">
        <div>
            <h4 class="fw-bold mb-0"><i class="bi bi-speedometer2"></i> PowerPuff Pets Control Panel</h4>
            <small class="text-muted text-light">Logged in as Administrator</small>
        </div>
        <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm rounded-pill px-3">Return to Site</a>
    </div>

    <div class="container py-5">
        
        @if(session('success'))
            <div class="alert alert-success shadow-sm mb-4">{{ session('success') }}</div>
        @endif

        <ul class="nav nav-pills mb-4 gap-2 border-bottom pb-3" id="adminTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active rounded-pill fw-bold" id="approvals-tab" data-bs-toggle="pill" data-bs-target="#approvals" type="button" style="color: #2f3542;">
                    <i class="bi bi-patch-check-fill"></i> Pending Approvals
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link rounded-pill fw-bold" id="users-tab" data-bs-toggle="pill" data-bs-target="#users" type="button" style="color: #2f3542;">
                    <i class="bi bi-people-fill"></i> Manage Users
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link rounded-pill fw-bold" id="products-tab" data-bs-toggle="pill" data-bs-target="#products" type="button" style="color: #2f3542;">
                    <i class="bi bi-box-seam-fill"></i> Active Catalog
                </button>
            </li>
        </ul>

        <div class="tab-content" id="adminTabsContent">
            
            <div class="tab-pane fade show active" id="approvals" role="tabpanel">
                <div class="row g-4">
                    
                    <div class="col-12 col-lg-6">
                        <div class="card border-0 shadow-sm rounded-4 p-4">
                            <h5 class="fw-bold mb-3" style="color: #a52a2a;"><i class="bi bi-shop"></i> Pending Seller Applications</h5>
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Shop Name</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pendingSellers as $seller)
                                            <tr>
                                                <td><strong>{{ $seller->shop_name }}</strong></td>
                                                <td>
                                                    <form action="{{ route('admin.approve.seller', $seller->id) }}" method="POST" class="d-inline">@csrf<button class="btn btn-success btn-sm me-1">Approve</button></form>
                                                    <form action="{{ route('admin.reject.seller', $seller->id) }}" method="POST" class="d-inline">@csrf<button class="btn btn-outline-danger btn-sm">Deny</button></form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="2" class="text-muted text-center small py-3">No pending vendor applications.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="card border-0 shadow-sm rounded-4 p-4">
                            <h5 class="fw-bold mb-3" style="color: #a52a2a;"><i class="bi bi-tags-fill"></i> Pending Product Submissions</h5>
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product Title</th>
                                            <th>Price</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pendingProducts as $prod)
                                            <tr>
                                                <td><strong>{{ $prod->name }}</strong></td>
                                                <td>₱{{ number_format($prod->price, 2) }}</td>
                                                <td>
                                                    <form action="{{ route('admin.approve.product', $prod->id) }}" method="POST" class="d-inline">@csrf<button class="btn btn-success btn-sm me-1">Approve</button></form>
                                                    <form action="{{ route('admin.reject.product', $prod->id) }}" method="POST" class="d-inline">@csrf<button class="btn btn-outline-danger btn-sm">Reject</button></form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="3" class="text-muted text-center small py-3">No pending product listings to audit.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>

            <div class="tab-pane fade" id="users" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-people-fill"></i> User Directory & Access Management</h5>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Account Name</th>
                                    <th>Email Address</th>
                                    <th>Assigned Privilege Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td><strong>{{ $user->name }}</strong></td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST" class="d-flex gap-2 align-items-center">
                                                @csrf
                                                <select name="role" class="form-select form-select-sm w-auto">
                                                    <option value="customer" {{ $user->role === 'customer' ? 'selected' : '' }}>Customer</option>
                                                    <option value="seller" {{ $user->role === 'seller' ? 'selected' : '' }}>Seller</option>
                                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                                </select>
                                                <button type="submit" class="btn btn-sm btn-dark">Save</button>
                                            </form>
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to nuke this user record completely?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Drop</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="products" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-box-seam-fill"></i> Published Live Storefront Listings</h5>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Product Item Name</th>
                                    <th>Listed Unit Price</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td><strong>{{ $product->name }}</strong></td>
                                        <td class="text-success fw-bold">₱{{ number_format($product->price, 2) }}</td>
                                        <td>
                                            <form action="{{ route('admin.products.delete', $product->id) }}" method="POST" onsubmit="return confirm('Remove item entry?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i> Remove</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        
    </div>
</div>

<style>
    .nav-pills .nav-link.active {
        background-color: #a52a2a !important;
        color: #ffffff !important;
    }
    .nav-pills .nav-link:hover:not(.active) {
        background-color: #e4e5e9;
    }
</style>

@endsection