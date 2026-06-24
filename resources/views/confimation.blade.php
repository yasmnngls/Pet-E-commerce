@extends('common.main')
@section('title', 'Order Confirmed!')
@section('content')

<div class="container mt-5 mb-5" style="max-width: 760px;">

    {{-- Success Banner --}}
    <div class="text-center mb-5">
        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm"
             style="width: 80px; height: 80px; background-color: #198754;">
            <i class="bi bi-check-lg text-white" style="font-size: 2.5rem;"></i>
        </div>
        <h2 class="fw-bold text-dark mb-1">Order Placed!</h2>
        <p class="text-muted">Thank you for shopping at <strong style="color: brown;">PowerPuff Pets</strong>. Your furry friend will love it!</p>
    </div>

    {{-- Order Reference --}}
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <div class="row g-3 text-center">
            <div class="col-6 col-md-3">
                <small class="text-muted d-block">Order Number</small>
                <strong class="font-monospace" style="color: brown;">{{ $order->order_number }}</strong>
            </div>
            <div class="col-6 col-md-3">
                <small class="text-muted d-block">Date</small>
                <strong>{{ $order->created_at->format('M d, Y') }}</strong>
            </div>
            <div class="col-6 col-md-3">
                <small class="text-muted d-block">Payment</small>
                <strong class="text-capitalize">{{ $order->payment_method }}</strong>
            </div>
            <div class="col-6 col-md-3">
                <small class="text-muted d-block">Status</small>
                <span class="badge rounded-pill bg-warning text-dark fw-bold text-capitalize">{{ $order->status }}</span>
            </div>
        </div>
    </div>

    {{-- Items Ordered --}}
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <h6 class="fw-bold mb-3" style="color: brown;"><i class="bi bi-box-seam me-2"></i>Items Ordered</h6>

        @foreach($order->items as $orderItem)
            @php $product = $orderItem->item; @endphp
            <div class="d-flex align-items-center gap-3 py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                <img src="{{ asset($product->image ?? 'images/pet3.jpg') }}"
                     alt="{{ $product->name ?? 'Product' }}"
                     class="rounded-3 border bg-light p-1"
                     style="width: 60px; height: 60px; object-fit: contain;"
                    >
                <div class="flex-grow-1">
                    <p class="mb-0 fw-bold small text-dark">{{ $product->name ?? 'Product no longer available' }}</p>
                    <small class="text-muted">Qty: {{ $orderItem->quantity }} &bull; ₱{{ number_format($orderItem->price, 2) }} each</small>
                </div>
                <span class="fw-bold small" style="color: brown;">
                    ₱{{ number_format($orderItem->price * $orderItem->quantity, 2) }}
                </span>
            </div>
        @endforeach

        <div class="d-flex justify-content-between fw-bold mt-3 pt-3 border-top">
            <span>Total</span>
            <span style="color: brown; font-size: 1.1rem;">₱{{ number_format($order->total, 2) }}</span>
        </div>
    </div>

    {{-- Delivery Address --}}
    @if($order->address)
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <h6 class="fw-bold mb-2" style="color: brown;"><i class="bi bi-geo-alt-fill me-2"></i>Delivering To</h6>
            <p class="mb-0 fw-medium text-dark">{{ $order->address->full_name }}</p>
            <p class="mb-0 text-muted small">{{ $order->address->phone }}</p>
            <p class="mb-0 text-muted small">
                {{ $order->address->street }}, {{ $order->address->barangay }},
                {{ $order->address->city }}, {{ $order->address->province }}
            </p>
        </div>
    @endif

    {{-- Actions --}}
    <div class="d-flex gap-3 justify-content-center flex-wrap">
        <a href="{{ route('orders.index') }}" class="btn text-white rounded-pill px-4 fw-bold shadow-sm" style="background-color: brown;">
            <i class="bi bi-bag-check me-1"></i> View My Orders
        </a>
        <a href="{{ route('landing') }}" class="btn btn-light border rounded-pill px-4 fw-medium">
            Continue Shopping
        </a>
    </div>

</div>

@endsection