@extends('common.main')
@section('title', 'Admin Backrooms')
@section('content')

<div class="container mt-5 mb-5">
    <h2 class="fw-bold mb-4"><i class="bi bi-speedometer2"></i> Admin Backrooms</h2>

    <ul class="nav nav-tabs mb-4" id="adminTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold text-dark" data-bs-toggle="tab" data-bs-target="#sellers" type="button">Pending Sellers ({{ $pendingSellers->count() }})</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold text-dark" data-bs-toggle="tab" data-bs-target="#products" type="button">Pending Products ({{ $pendingProducts->count() }})</button>
        </li>
    </ul>

    <div class="tab-content" id="adminTabsContent">
        
        {{-- SELLER APPLICATIONS TAB --}}
        <div class="tab-pane fade show active" id="sellers">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Store Name</th>
                            <th>Applicant</th>
                            <th>Applied On</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingSellers as $app)
                        <tr>
                            <td class="fw-bold">{{ $app->store_name }}</td>
                            <td>{{ $app->user->name }} ({{ $app->user->email }})</td>
                            <td>{{ $app->created_at->format('M d, Y') }}</td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#sellerModal{{ $app->id }}">
                                    Review Form
                                </button>
                            </td>
                        </tr>

                        <div class="modal fade" id="sellerModal{{ $app->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content rounded-4">
                                    <div class="modal-header border-0 pb-0">
                                        <h5 class="modal-title fw-bold">Review Seller Application</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body py-4">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <p class="mb-1 text-muted small">Store Name</p>
                                                <h6 class="fw-bold">{{ $app->store_name }}</h6>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1 text-muted small">Store Type</p>
                                                <h6 class="fw-bold">{{ $app->store_type }}</h6>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1 text-muted small">Legal Name</p>
                                                <h6 class="fw-bold">{{ $app->legal_name }}</h6>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1 text-muted small">Contact Email</p>
                                                <h6 class="fw-bold">{{ $app->customer_support_contact }}</h6>
                                            </div>
                                            <div class="col-12">
                                                <p class="mb-1 text-muted small">Business Address</p>
                                                <h6 class="fw-bold">{{ $app->business_address }}</h6>
                                            </div>
                                            <hr class="my-3">
                                            <div class="col-md-4">
                                                <p class="mb-1 text-muted small">Bank Name</p>
                                                <h6 class="fw-bold">{{ $app->bank_name }}</h6>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="mb-1 text-muted small">Account Holder</p>
                                                <h6 class="fw-bold">{{ $app->bank_account_holder }}</h6>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="mb-1 text-muted small">Account Number</p>
                                                <h6 class="fw-bold">{{ $app->bank_account_number }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 bg-light rounded-bottom-4">
                                        <form action="{{ route('admin.reject.seller', $app->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger px-4 rounded-pill">Reject</button>
                                        </form>
                                        <form action="{{ route('admin.approve.seller', $app->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success px-4 rounded-pill">Approve Seller</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No pending seller applications.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PRODUCT APPLICATIONS TAB --}}
        <div class="tab-pane fade" id="products">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Product Image</th>
                            <th>Product Name</th>
                            <th>Seller</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingProducts as $product)
                        <tr>
                            <td>
                                <img src="{{ $product->featured_image }}" alt="Product" class="rounded-3 border" style="width: 50px; height: 50px; object-fit: cover;">
                            </td>
                            <td class="fw-bold">{{ $product->name }}</td>
                            <td>{{ $product->seller->name ?? 'Unknown' }}</td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#productModal{{ $product->id }}">
                                    Review Product
                                </button>
                            </td>
                        </tr>

                        <div class="modal fade" id="productModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content rounded-4">
                                    <div class="modal-header border-0 pb-0">
                                        <h5 class="modal-title fw-bold">Review Product Submission</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body py-4">
                                        <div class="text-center mb-4">
                                            <img src="{{ $product->featured_image }}" alt="{{ $product->name }}" class="rounded-3 img-fluid border" style="max-height: 200px;">
                                        </div>
                                        <h5 class="fw-bold mb-2">{{ $product->name }}</h5>
                                        <p class="text-muted small mb-3">{{ $product->description }}</p>
                                        
                                        <ul class="list-group list-group-flush border-top border-bottom">
                                            <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0">
                                                <span class="text-muted small">Category</span>
                                                <span class="fw-bold">{{ $product->category->name ?? 'N/A' }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0">
                                                <span class="text-muted small">Price</span>
                                                <span class="fw-bold text-success">₱{{ number_format($product->price, 2) }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0">
                                                <span class="text-muted small">Stock Quantity</span>
                                                <span class="fw-bold">{{ $product->stock_quantity }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="modal-footer border-0 bg-light rounded-bottom-4">
                                        <form action="{{ route('admin.reject.product', $product->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger px-4 rounded-pill">Reject</button>
                                        </form>
                                        <form action="{{ route('admin.approve.product', $product->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success px-4 rounded-pill">Approve Product</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No pending product submissions.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection