@extends('common.main')
@section('title', 'My Cart')
@section('content')

<div class="container mt-5 mb-5" style="max-width: 960px;">

    <h3 class="fw-bold text-uppercase mb-4" style="color: brown;">
        <i class="bi bi-cart3"></i> My Cart
    </h3>

    @if(session('success'))
        <div class="alert alert-success rounded-3 shadow-sm">{{ session('success') }}</div>
    @endif

    @if($items->isEmpty())
        <div class="text-center py-5 bg-white rounded-4 shadow-sm border">
            <i class="bi bi-cart-x text-muted d-block mb-2" style="font-size: 3.5rem;"></i>
            <h5 class="fw-bold text-dark mb-1">Your cart is empty</h5>
            <p class="text-muted small mb-3">Browse our products and add something for your pet!</p>
            <a href="{{ route('landing') }}" class="btn btn-sm text-white rounded-pill px-4 fw-bold shadow-sm" style="background-color: brown;">
                Continue Shopping
            </a>
        </div>
    @else
        <div class="row g-4">

            {{-- Cart Items --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 p-3">
                    @foreach($items as $cartItem)
                        @php $product = $cartItem->item; @endphp
                        <div class="d-flex align-items-center gap-3 py-3 {{ !$loop->last ? 'border-bottom' : '' }}">

                            {{-- Product Image --}}
                            <img src="{{ asset($product->image ?? 'images/pet3.jpg') }}"
                                 alt="{{ $product->name }}"
                                 class="rounded-3 border bg-light p-1"
                                 style="width: 80px; height: 80px; object-fit: contain;">

                            {{-- Product Info --}}
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-0 text-dark">{{ $product->name }}</h6>
                                <small class="text-muted">₱{{ number_format($product->price, 2) }} each</small>
                            </div>

                            {{-- Quantity Control --}}
                            <form action="{{ route('cart.update', $cartItem->id) }}" method="POST" class="d-flex align-items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="quantity" value="{{ $cartItem->quantity }}"
                                       min="1" class="form-control form-control-sm text-center"
                                       style="width: 65px;">
                                <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill px-2">
                                    Update
                                </button>
                            </form>

                            {{-- Line Total --}}
                            <div class="text-end" style="min-width: 80px;">
                                <span class="fw-bold" style="color: brown;">
                                    ₱{{ number_format($product->price * $cartItem->quantity, 2) }}
                                </span>
                            </div>

                            {{-- Remove --}}
                            <form action="{{ route('cart.remove', $cartItem->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-light btn-sm rounded-circle border text-danger"
                                        style="width: 32px; height: 32px;" title="Remove">
                                    <i class="bi bi-x"></i>
                                </button>
                            </form>

                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h5 class="fw-bold mb-3" style="color: brown;">Order Summary</h5>

                    <div class="d-flex justify-content-between mb-2 small text-muted">
                        <span>Subtotal ({{ $items->sum('quantity') }} items)</span>
                        <span>₱{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 small text-muted">
                        <span>Shipping</span>
                        <span class="text-success fw-medium">Calculated at checkout</span>
                    </div>

                    <hr class="my-2">

                    <div class="d-flex justify-content-between fw-bold mb-4">
                        <span>Total</span>
                        <span style="color: brown; font-size: 1.2rem;">₱{{ number_format($subtotal, 2) }}</span>
                    </div>

                    <a href="{{ route('checkout.index') }}"
                       class="btn text-white w-100 rounded-pill fw-bold py-2 shadow-sm"
                       style="background-color: brown;">
                        Proceed to Checkout <i class="bi bi-arrow-right ms-1"></i>
                    </a>

                    <a href="{{ route('landing') }}" class="btn btn-light w-100 rounded-pill fw-medium py-2 mt-2 border">
                        Continue Shopping
                    </a>
                </div>
            </div>

        </div>
    @endif
</div>

@endsection