@extends('common.main2')
@section('title', 'Admin Backrooms Dashboard')
@section('content')

<div class="w-100 py-3 px-4 mb-4 d-flex align-items-center justify-content-between shadow-sm text-white shadow-sm" style="background-color: #212529;">
    <div class="d-flex align-items-center gap-2">
        <i class="bi bi-shield-lock-fill fs-4 text-warning"></i>
        <div>
            <h5 class="fw-bold mb-0" style="letter-spacing: -0.5px;">PowerPuff Pets</h5>
            <small class="text-uppercase tracking-wider opacity-75 style-font" style="font-size: 0.65rem;">Backoffice Control Environment</small>
        </div>
    </div>
    
    <form action="{{ route('logout') }}" method="POST" class="m-0">
        @csrf
        <button type="submit" class="btn btn-sm btn-outline-light rounded-pill px-3 fw-bold border-opacity-25" onmouseover="this.style.backgroundColor='#a52a2a'" onmouseout="this.style.backgroundColor='transparent'">
            <i class="bi bi-box-arrow-right me-1"></i> Exit Portal
        </button>
    </form>
</div>

<div class="container mb-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4 px-1">
        <div>
            <h2 class="fw-bold mb-1 text-dark" style="letter-spacing: -1px;">Admin Backrooms</h2>
            <p class="text-muted small mb-0">System control hub and pending submission queues.</p>
        </div>
        @if(session('success'))
            <div class="alert alert-success rounded-pill px-4 py-2 m-0 shadow-sm small border-0 bg-white" style="color: #2e7d32;"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</div>
        @endif
    </div>

    <div class="row g-3 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase tracking-wider">Platform Base</span>
                        <h3 class="fw-bold mb-0 mt-1 text-dark">{{ $usersCount }} Users</h3>
                    </div>
                    <div class="p-3 rounded-circle bg-light text-dark"><i class="bi bi-people fs-4"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white" style="border-left: 4px solid #a52a2a !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase tracking-wider">Seller Queue</span>
                        <h3 class="fw-bold mb-0 mt-1" style="color: #a52a2a;">{{ $pendingSellers->count() }} Waiting</h3>
                    </div>
                    <div class="p-3 rounded-circle text-white" style="background-color: #a52a2a;"><i class="bi bi-shop fs-4"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase tracking-wider">Product Queue</span>
                        <h3 class="fw-bold mb-0 mt-1 text-dark">{{ $pendingProducts->count() }} Submissions</h3>
                    </div>
                    <div class="p-3 rounded-circle bg-light text-muted"><i class="bi bi-box-seam fs-4"></i></div>
                </div>
            </div>
        </div>
    </div>

    <ul class="nav nav-pills mb-4 gap-2 bg-white p-2 rounded-4 shadow-sm" id="adminTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold px-4 py-2.5 rounded-3 tab-custom-btn" data-bs-toggle="tab" data-bs-target="#users" type="button">
                <i class="bi bi-people-fill me-2"></i>All Users
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold text-muted px-4 py-2.5 rounded-3 tab-custom-btn" data-bs-toggle="tab" data-bs-target="#sellers" type="button">
                <i class="bi bi-card-list me-2"></i>Pending Sellers
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold text-muted px-4 py-2.5 rounded-3 tab-custom-btn" data-bs-toggle="tab" data-bs-target="#products" type="button">
                <i class="bi bi-bag-plus-fill me-2"></i>Pending Products
            </button>
        </li>
    </ul>

    <div class="tab-content" id="adminTabsContent">
        
        {{-- TAB 1: ALL USERS --}}
        <div class="tab-pane fade show active" id="users">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background-color: #212529; color: #ffffff;">
                            <tr>
                                <th class="ps-4 py-3">User ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role Modifier</th>
                                <th>Joined On</th>
                                <th class="text-end pe-4">Account Management</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr class="border-bottom border-light">
                                <td class="ps-4 fw-bold text-muted" style="font-size: 0.9rem;">#{{ $user->id }}</td>
                                <td class="fw-bold text-dark">{{ $user->name }}</td>
                                <td class="text-muted">{{ $user->email }}</td>
                                <td>
                                    <form action="{{ route('admin.update.user.role', $user->id) }}" method="POST" class="m-0">
                                        @csrf
                                        @method('PUT')
                                        <select name="role" class="form-select form-select-sm w-auto rounded-3 border-secondary-subtle font-semibold" onchange="this.form.submit()" style="font-size: 0.85rem;">
                                            <option value="customer" {{ $user->role === 'customer' ? 'selected' : '' }}>Customer</option>
                                            <option value="seller" {{ $user->role === 'seller' ? 'selected' : '' }}>Seller</option>
                                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="text-muted" style="font-size: 0.9rem;">{{ $user->created_at->format('M d, Y') }}</td>
                                <td class="text-end pe-4">
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.delete.user', $user->id) }}" method="POST" class="m-0" onsubmit="return confirm('Are you sure you want to permanently remove this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger text-decoration-none rounded-pill p-1 px-3 border border-danger-subtle hover-bg-danger transition-all" style="font-size: 0.85rem;">
                                                <i class="bi bi-trash3"></i> Drop User
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge bg-dark-subtle text-dark border px-3 py-2 rounded-pill font-bold" style="font-size: 0.75rem;">Current Admin</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5 fw-medium">No registered system users found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        {{-- TAB 2: SELLER APPLICATIONS --}}
        <div class="tab-pane fade" id="sellers">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background-color: #212529; color: #ffffff;">
                            <tr>
                                <th class="ps-4 py-3">Store Name</th>
                                <th>Applicant Profile</th>
                                <th>Submission Date</th>
                                <th class="text-end pe-4">Verification Audit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingSellers as $app)
                            <tr class="border-bottom border-light">
                                <td class="ps-4 fw-bold text-dark" style="font-size: 1rem;">{{ $app->store_name }}</td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $app->user->name }}</div>
                                    <small class="text-muted">{{ $app->user->email }}</small>
                                </td>
                                <td class="text-muted">{{ $app->created_at->format('M d, Y') }}</td>
                                <td class="text-end pe-4">
                                    <button type="button" class="btn btn-sm btn-dark rounded-pill px-4 fw-bold" data-bs-toggle="modal" data-bs-target="#sellerModal{{ $app->id }}" style="background-color: #212529;">
                                        Review Form
                                    </button>
                                </td>
                            </tr>

                            <div class="modal fade" id="sellerModal{{ $app->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg rounded-4">
                                        <div class="modal-header border-0 pb-0 pt-4 px-4">
                                            <h5 class="modal-title fw-bold text-dark"><i class="bi bi-clipboard-check me-2" style="color: #a52a2a;"></i>Review Application</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body py-4 px-4">
                                            <div class="row g-3 bg-light p-3 rounded-4 mb-3 mx-0">
                                                <div class="col-md-6">
                                                    <small class="text-muted text-uppercase tracking-wider font-bold d-block" style="font-size: 0.65rem;">Store Name</small>
                                                    <span class="fw-bold text-dark fs-5">{{ $app->store_name }}</span>
                                                </div>
                                                <div class="col-md-6">
                                                    <small class="text-muted text-uppercase tracking-wider font-bold d-block" style="font-size: 0.65rem;">Business Structure</small>
                                                    <span class="fw-bold text-dark">{{ $app->store_type }}</span>
                                                </div>
                                            </div>
                                            
                                            <div class="row g-3 px-1">
                                                <div class="col-md-6">
                                                    <p class="mb-1 text-muted small font-bold">Legal Corporate Entity Name</p>
                                                    <h6 class="fw-semibold text-dark">{{ $app->legal_name }}</h6>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="mb-1 text-muted small font-bold">Customer Relations Email</p>
                                                    <h6 class="fw-semibold text-dark">{{ $app->customer_support_contact }}</h6>
                                                </div>
                                                <div class="col-12 mt-3">
                                                    <p class="mb-1 text-muted small font-bold">Physical Store/HQ Address</p>
                                                    <h6 class="fw-semibold text-dark">{{ $app->business_address }}</h6>
                                                </div>
                                            </div>

                                            <hr class="my-4 opacity-25">
                                            
                                            <h6 class="fw-bold text-muted small text-uppercase tracking-wider mb-3"><i class="bi bi-bank me-2"></i>Financial Remittance Target Account</h6>
                                            <div class="row g-3 p-3 rounded-4 mx-0" style="background-color: #ffff;">
                                                <div class="col-md-4">
                                                    <small class="text-muted d-block" style="font-size: 0.7rem;">Bank Name</small>
                                                    <span class="fw-bold text-dark">{{ $app->bank_name }}</span>
                                                </div>
                                                <div class="col-md-4">
                                                    <small class="text-muted d-block" style="font-size: 0.7rem;">Account Holder</small>
                                                    <span class="fw-bold text-dark">{{ $app->bank_account_holder }}</span>
                                                </div>
                                                <div class="col-md-4">
                                                    <small class="text-muted d-block" style="font-size: 0.7rem;">Account Number</small>
                                                    <span class="fw-bold text-dark font-monospace">{{ $app->bank_account_number }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 bg-light p-3 px-4 rounded-bottom-4 d-flex justify-content-between">
                                            <form action="{{ route('admin.reject.seller', $app->id) }}" method="POST" class="m-0">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger px-4 rounded-pill fw-bold">Deny Request</button>
                                            </form>
                                            <form action="{{ route('admin.approve.seller', $app->id) }}" method="POST" class="m-0">
                                                @csrf
                                                <button type="submit" class="btn text-white px-4 rounded-pill fw-bold" style="background-color: #a52a2a;">Approve Vendor</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5 fw-medium">No pending seller application records found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- TAB 3: PENDING PRODUCTS --}}
        <div class="tab-pane fade" id="products">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background-color: #212529; color: #ffffff;">
                            <tr>
                                <th class="ps-4 py-3">Display Asset</th>
                                <th>Product Item</th>
                                <th>Sourced Vendor</th>
                                <th class="text-end pe-4">Catalog Compliance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingProducts as $product)
                            <tr class="border-bottom border-light">
                                <td class="ps-4">
                                    <img src="{{ $product->featured_image }}" alt="{{ $product->name }}" class="rounded-3 border shadow-sm" style="width: 52px; height: 52px; object-fit: cover;">
                                </td>
                                <td class="fw-bold text-dark" style="font-size: 0.95rem;">{{ $product->name }}</td>
                                <td class="fw-semibold text-muted"><i class="bi bi-shop me-1 small"></i>{{ $product->seller->name ?? 'Unknown Store' }}</td>
                                <td class="text-end pe-4">
                                    <button type="button" class="btn btn-sm btn-dark rounded-pill px-4 fw-bold" data-bs-toggle="modal" data-bs-target="#productModal{{ $product->id }}" style="background-color: #212529;">
                                        Review Details
                                    </button>
                                </td>
                            </tr>

                            <div class="modal fade" id="productModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg rounded-4">
                                        <div class="modal-header border-0 pb-0 pt-4 px-4">
                                            <h5 class="modal-title fw-bold text-dark">Analyze Item Submission</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body py-4 px-4">
                                            <div class="text-center mb-4 bg-light p-3 rounded-4 border">
                                                <img src="{{ $product->featured_image }}" alt="{{ $product->name }}" class="rounded-3 shadow-sm img-fluid" style="max-height: 180px; object-fit: contain;">
                                            </div>
                                            <h5 class="fw-bold text-dark mb-1">{{ $product->name }}</h5>
                                            <p class="text-muted small mb-4" style="line-height: 1.5;">{{ $product->description }}</p>
                                            
                                            <div class="list-group list-group-flush rounded-3 border">
                                                <div class="list-group-item d-flex justify-content-between align-items-center bg-transparent py-2.5">
                                                    <span class="text-muted small fw-semibold">Target Department</span>
                                                    <span class="badge bg-secondary rounded-pill font-bold px-3 py-1.5">{{ $product->category->name ?? 'General' }}</span>
                                                </div>
                                                <div class="list-group-item d-flex justify-content-between align-items-center bg-transparent py-2.5">
                                                    <span class="text-muted small fw-semibold">Marketplace Retail Price</span>
                                                    <span class="fw-bold text-dark fs-5">₱{{ number_format($product->price, 2) }}</span>
                                                </div>
                                                <div class="list-group-item d-flex justify-content-between align-items-center bg-transparent py-2.5">
                                                    <span class="text-muted small fw-semibold">Initial Stock Supply</span>
                                                    <span class="fw-bold text-dark">{{ $product->stock_quantity }} units</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 bg-light p-3 px-4 rounded-bottom-4 d-flex justify-content-between">
                                            <form action="{{ route('admin.reject.product', $product->id) }}" method="POST" class="m-0">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger px-4 rounded-pill fw-bold">Reject Item</button>
                                            </form>
                                            <form action="{{ route('admin.approve.product', $product->id) }}" method="POST" class="m-0">
                                                @csrf
                                                <button type="submit" class="btn text-white px-4 rounded-pill fw-bold" style="background-color: #a52a2a;">Publish to Shop</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5 fw-medium">No pending product listings are currently waiting.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    body {
        background-color: #fbeee0 !important; /* Forces layout context background sync with the login aesthetic */
    }
    .tab-custom-btn {
        color: #6c757d !important;
        transition: all 0.2s ease-in-out;
    }
    .tab-custom-btn.active {
        background-color: #212529 !important;
        color: #ffffff !important;
    }
    .tab-custom-btn:hover:not(.active) {
        background-color: #f8f9fa !important;
        color: #212529 !important;
    }
    .hover-bg-danger:hover {
        background-color: #dc3545 !important;
        color: #ffffff !important;
        border-color: #dc3545 !important;
    }
</style>

@endsection