@extends('common.main')
@section('title', $product->name)
@section('content')

<div class="container mt-5 mb-5" style="max-width: 1100px;">

    @if(session('success'))
        <div class="alert alert-success rounded-3 shadow-sm mb-4">{{ session('success') }}</div>
    @endif

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('landing') }}" style="color: brown;">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('landing') }}" style="color: brown;">{{ $product->category->name ?? 'Products' }}</a></li>
            <li class="breadcrumb-item active text-muted">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-5">

        {{-- LEFT: Product Image --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white p-4 text-center">
                <img src="{{ $product->featured_image }}"
                     alt="{{ $product->name }}"
                     class="img-fluid mx-auto"
                     style="max-height: 380px; object-fit: contain;">
            </div>
        </div>

        {{-- RIGHT: Product Info --}}
        <div class="col-lg-7 d-flex flex-column justify-content-center">

            {{-- Category badge --}}
            @if($product->category)
                <span class="badge rounded-pill mb-2 d-inline-block"
                      style="background-color: #f5e6d3; color: brown; width: fit-content;">
                    {{ $product->category->name }}
                </span>
            @endif

            <h2 class="fw-bold text-dark mb-2">{{ $product->name }}</h2>

            {{-- Seller --}}
            <p class="text-muted small mb-3">
                <i class="bi bi-shop me-1"></i>
                Sold by <strong>{{ $product->seller->name ?? 'PowerPuff Pets' }}</strong>
            </p>

            {{-- Price --}}
            <div class="mb-3">
                <span class="fw-bold" style="color: brown; font-size: 2rem;">
                    ₱{{ number_format($product->price, 2) }}
                </span>
            </div>

            {{-- Stock status --}}
            @if($product->stock_quantity > 0)
                <p class="text-success small fw-medium mb-4">
                    <i class="bi bi-check-circle-fill me-1"></i>
                    In Stock ({{ $product->stock_quantity }} available)
                </p>
            @else
                <p class="text-danger small fw-medium mb-4">
                    <i class="bi bi-x-circle-fill me-1"></i>
                    Out of Stock
                </p>
            @endif

            {{-- Add to Cart --}}
            @if($product->stock_quantity > 0)
                @auth
                    <form action="{{ route('cart.add') }}" method="POST" class="d-flex align-items-center gap-3 mb-3">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="d-flex align-items-center border rounded-3">
                            <span class="px-3 text-muted small fw-medium">Qty</span>
                            <input type="number" name="quantity" value="1" min="1"
                                   max="{{ $product->stock_quantity }}"
                                   class="form-control form-control-sm border-0 text-center"
                                   style="width: 65px;">
                        </div>
                        <button type="submit"
                                class="btn text-white rounded-pill px-4 fw-bold py-2 shadow-sm"
                                style="background-color: brown;">
                            <i class="bi bi-cart-plus me-1"></i> Add to Cart
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="btn text-white rounded-pill px-4 fw-bold py-2 shadow-sm mb-3 d-inline-block"
                       style="background-color: brown;">
                        <i class="bi bi-cart-plus me-1"></i> Add to Cart
                    </a>
                @endauth
            @endif

            <a href="{{ route('cart.index') }}"
               class="btn btn-light border rounded-pill px-4 fw-medium py-2 d-inline-block"
               style="width: fit-content;">
                <i class="bi bi-cart3 me-1"></i> View Cart
            </a>

        </div>
    </div>

    {{-- Description --}}
    @if($product->description)
        <div class="card border-0 shadow-sm rounded-4 p-4 mt-5">
            <h5 class="fw-bold mb-3" style="color: brown;">Product Description</h5>
            <p class="text-muted mb-0" style="line-height: 1.8;">{{ $product->description }}</p>
        </div>
    @endif

    {{-- Related Products --}}
    @if($related->isNotEmpty())
        <div class="mt-5">
            <h5 class="fw-bold text-uppercase mb-4" style="color: brown;">You Might Also Like</h5>
            <div class="row row-cols-2 row-cols-md-4 g-4">
                @foreach($related as $rel)
                    <div class="col">
                        <a href="{{ route('product.show', $rel->slug) }}" class="text-decoration-none">
                            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden product-card">
                                <img src="{{ asset($rel->image ?? 'images/pet3.jpg') }}"
                                     class="card-img-top p-3"
                                     alt="{{ $rel->name }}"
                                     style="height: 150px; object-fit: contain;">
                                <div class="card-body pt-0">
                                    <small class="text-muted d-block mb-1">
                                        <i class="bi bi-shop me-1"></i>{{ $rel->seller->name ?? 'PowerPuff Pets' }}
                                    </small>
                                    <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">{{ $rel->name }}</h6>
                                    <span class="fw-bold" style="color: brown;">₱{{ number_format($rel->price, 2) }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>

<style>
    .product-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .product-card:hover { transform: translateY(-4px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.12) !important; }
</style>

@endsection