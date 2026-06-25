@extends('common.main')
@section('title', 'Checkout')
@section('content')

<div class="container mt-5 mb-5" style="max-width: 1100px;">

    <h3 class="fw-bold text-uppercase mb-4" style="color: brown;">
        <i class="bi bi-bag-check"></i> Checkout
    </h3>

    @if($errors->any())
        <div class="alert alert-danger rounded-3 shadow-sm mb-4">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf

        <div class="row g-4">

            {{-- LEFT: Address + Payment --}}
            <div class="col-lg-7">

                {{-- Delivery Address --}}
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <h5 class="fw-bold mb-3" style="color: brown;"><i class="bi bi-geo-alt-fill me-2"></i>Delivery Address</h5>

                    @if(!empty($savedAddresses) && $savedAddresses->isNotEmpty())
                        <p class="small fw-medium text-muted mb-2">Select a saved address, or fill in a new one below:</p>
                        @foreach($savedAddresses ?? collect() as $addr)
                            <div class="form-check border rounded-3 p-3 mb-2">
                                <input class="form-check-input" type="radio" name="address_option"
                                       id="addr_{{ $addr->id }}" value="saved_{{ $addr->id }}"
                                       {{ old('address_option') === 'saved_'.$addr->id ? 'checked' : '' }}>
                                <label class="form-check-label w-100" for="addr_{{ $addr->id }}">
                                    <strong>{{ $addr->full_name }}</strong> &bull; {{ $addr->phone }}<br>
                                    <small class="text-muted">{{ $addr->street }}, {{ $addr->barangay }}, {{ $addr->city }}, {{ $addr->province }}</small>
                                </label>
                            </div>
                        @endforeach
                        <div class="form-check border rounded-3 p-3 mb-3">
                            <input class="form-check-input" type="radio" name="address_option"
                                   id="addr_new" value="new"
                                   {{ old('address_option', 'new') === 'new' ? 'checked' : '' }}>
                            <label class="form-check-label fw-medium" for="addr_new">Enter a new address</label>
                        </div>
                    @else
                        <input type="hidden" name="address_option" value="new">
                    @endif

                    <p class="small fw-medium text-muted mb-2 mt-1">New address:</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">Full Name</label>
                            <input type="text" name="full_name" class="form-control rounded-3"
                                   value="{{ old('full_name') }}" placeholder="Juan Dela Cruz">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">Phone Number</label>
                            <input type="text" name="phone" class="form-control rounded-3"
                                   value="{{ old('phone') }}" placeholder="09XX XXX XXXX">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-medium">Street / House No.</label>
                            <input type="text" name="street" class="form-control rounded-3"
                                   value="{{ old('street') }}" placeholder="123 Mabini St.">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">Barangay</label>
                            <input type="text" name="barangay" class="form-control rounded-3"
                                   value="{{ old('barangay') }}" placeholder="Brgy. San Roque">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">City / Municipality</label>
                            <input type="text" name="city" class="form-control rounded-3"
                                   value="{{ old('city') }}" placeholder="Quezon City">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">Province</label>
                            <input type="text" name="province" class="form-control rounded-3"
                                   value="{{ old('province') }}" placeholder="Metro Manila">
                        </div>
                    </div>
                </div>

                {{-- Payment Method --}}
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h5 class="fw-bold mb-3" style="color: brown;"><i class="bi bi-credit-card-fill me-2"></i>Payment Method</h5>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="d-flex align-items-center gap-3 border rounded-3 p-3" style="cursor: pointer;">
                                <input class="form-check-input m-0" type="radio" name="payment_method" value="cod"
                                       {{ old('payment_method', 'cod') === 'cod' ? 'checked' : '' }}>
                                <div>
                                    <span class="fw-bold d-block">Cash on Delivery</span>
                                    <small class="text-muted">Pay when your order arrives</small>
                                </div>
                                <i class="bi bi-cash-coin ms-auto fs-4 text-success"></i>
                            </label>
                        </div>
                        <div class="col-12">
                            <label class="d-flex align-items-center gap-3 border rounded-3 p-3" style="cursor: pointer;">
                                <input class="form-check-input m-0" type="radio" name="payment_method" value="gcash"
                                       {{ old('payment_method') === 'gcash' ? 'checked' : '' }}>
                                <div>
                                    <span class="fw-bold d-block">GCash</span>
                                    <small class="text-muted">Pay via GCash e-wallet</small>
                                </div>
                                <span class="ms-auto fw-bold text-primary" style="font-size: 1.1rem;">G</span>
                            </label>
                        </div>
                        <div class="col-12">
                            <label class="d-flex align-items-center gap-3 border rounded-3 p-3" style="cursor: pointer;">
                                <input class="form-check-input m-0" type="radio" name="payment_method" value="card"
                                       {{ old('payment_method') === 'card' ? 'checked' : '' }}>
                                <div>
                                    <span class="fw-bold d-block">Credit / Debit Card</span>
                                    <small class="text-muted">Visa, Mastercard accepted</small>
                                </div>
                                <i class="bi bi-credit-card ms-auto fs-4 text-primary"></i>
                            </label>
                        </div>
                    </div>
                </div>

            </div>

            {{-- RIGHT: Order Summary --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 20px;">
                    <h5 class="fw-bold mb-3" style="color: brown;">Order Summary</h5>

                    <div class="d-flex flex-column gap-3 mb-3">
                        @foreach($items ?? collect() as $cartItem)
                            @php $product = $cartItem->item; @endphp
                            @if($product)
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ asset($product->image ?? 'images/pet3.jpg') }}"
                                         alt="{{ $product->name }}"
                                         class="rounded-3 border bg-light p-1"
                                         style="width: 55px; height: 55px; object-fit: contain;">
                                    <div class="flex-grow-1">
                                        <p class="mb-0 fw-bold small text-dark" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            {{ $product->name }}
                                        </p>
                                        <small class="text-muted">Qty: {{ $cartItem->quantity }}</small>
                                    </div>
                                    <span class="fw-bold small" style="color: brown;">
                                        ₱{{ number_format($product->price * $cartItem->quantity, 2) }}
                                    </span>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <hr class="my-2">

                    <div class="d-flex justify-content-between small text-muted mb-2">
                        <span>Subtotal</span>
                        <span>₱{{ number_format($subtotal ?? 0, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-3">
                        <span>Shipping</span>
                        <span class="text-success fw-medium">Free</span>
                    </div>

                    <hr class="my-2">

                    <div class="d-flex justify-content-between fw-bold mb-4">
                        <span>Total</span>
                        <span style="color: brown; font-size: 1.2rem;">₱{{ number_format($subtotal ?? 0, 2) }}</span>
                    </div>

                    <button type="submit" class="btn text-white w-100 rounded-pill fw-bold py-2 shadow-sm"
                            style="background-color: brown;">
                        Place Order <i class="bi bi-lock-fill ms-1"></i>
                    </button>

                    <a href="{{ route('cart.index') }}" class="btn btn-light w-100 rounded-pill fw-medium py-2 mt-2 border">
                        Back to Cart
                    </a>
                </div>
            </div>

        </div>
    </form>
</div>

@endsection