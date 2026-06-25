@extends('common.main')
@section('title', 'My Cart')
@section('content')

<div class="container mt-5 mb-5" style="max-width: 1000px;">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <h3 class="fw-bold text-uppercase" style="color: brown;">
            <i class="bi bi-cart3"></i> My Cart
        </h3>
        <a href="{{ route('products.catalog') }}" class="btn btn-light border rounded-pill fw-medium">
            <i class="bi bi-arrow-left me-1"></i> Continue Shopping
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-3 shadow-sm">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger rounded-3 shadow-sm">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @if($groupedItems->isEmpty())
        <div class="text-center py-5 bg-white rounded-4 shadow-sm border">
            <i class="bi bi-cart-x text-muted d-block mb-2" style="font-size: 3rem;"></i>
            <h5 class="fw-bold text-dark mb-1">Your cart is empty</h5>
            <p class="text-muted small mb-3">Looks like you haven't added anything yet.</p>
            <a href="{{ route('products.catalog') }}" class="btn text-white rounded-pill px-4 fw-bold" style="background-color: brown;">
                Browse Products
            </a>
        </div>
    @else
        <div class="card border-0 shadow-sm rounded-4 p-4">
            @foreach($groupedItems as $sellerId => $items)
                @php
                    $seller = $items->first()->item->seller ?? null;
                    $sellerName = $seller?->sellerApplication->store_name ?? $seller?->name ?? 'Unknown Seller';
                    $groupTotal = $items->sum(fn($cartItem) => $cartItem->item->price * $cartItem->quantity);
                @endphp

                <div class="mb-4 p-3 rounded-4 bg-light border">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                        <div class="d-flex align-items-center gap-3">
                            @php
                                $sa = $seller?->sellerApplication ?? null;
                            @endphp
                            <img src="{{ $sa?->logo_url ?? asset('images/default-store.png') }}" alt="Store" style="width:44px;height:44px;object-fit:cover;border-radius:8px;border:1px solid #ddd;">
                            <div>
                                <h6 class="fw-bold mb-1">{{ $sellerName }}</h6>
                                <small class="text-muted">Seller ID: {{ $sellerId ?: 'N/A' }}</small>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="text-muted small d-block">Store subtotal</span>
                            <strong class="text-dark">₱{{ number_format($groupTotal, 2) }}</strong>
                        </div>
                    </div>

                    @foreach($items as $cartItem)
                        @php $product = $cartItem->item; @endphp
                        @if($product)
                            <div class="d-flex align-items-center gap-4 py-3 border-top">
                                <img src="{{ $product->image_url }}"
                                         alt="{{ $product->name }}"
                                     class="rounded-3 border bg-light p-1"
                                     style="width: 80px; height: 80px; object-fit: contain;">

                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1 text-dark">{{ $product->name }}</h6>
                                    <small class="text-muted">₱{{ number_format($product->price, 2) }} each</small>
                                    <small class="text-muted d-block">Category: {{ $product->category->name ?? 'General' }}</small>
                                </div>

                                <div class="d-flex align-items-center gap-2">
                                    <form action="{{ route('cart.update', $cartItem->id) }}" method="POST" class="d-flex align-items-center">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $cartItem->quantity }}"
                                               min="1" max="{{ $product->stock_quantity }}"
                                               class="form-control form-control-sm text-center"
                                               style="width: 65px;" onchange="this.form.submit()">
                                    </form>
                                </div>

                                <span class="fw-bold" style="color: brown; min-width: 90px; text-align: right;">
                                    ₱{{ number_format($product->price * $cartItem->quantity, 2) }}
                                </span>

                                <form action="{{ route('cart.remove', $cartItem->id) }}" method="POST" class="m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link text-danger p-0" title="Remove item">
                                        <i class="bi bi-trash3 fs-5"></i>
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endforeach

            <div class="d-flex justify-content-between align-items-center mt-4 pt-3">
                <span class="fw-bold fs-5">Subtotal</span>
                <span class="fw-bold fs-5" style="color: brown;">₱{{ number_format($subtotal, 2) }}</span>
            </div>

            <div class="text-end mt-3">
                <a href="{{ route('checkout.index') }}" class="btn text-white rounded-pill px-5 py-2 fw-bold shadow-sm" style="background-color: brown;">
                    Proceed to Checkout <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    @endif
</div>

@endsection
