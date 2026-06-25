@extends('common.main')
@section('title', 'My Purchase Tracking')
@section('content')

@php
    $fallbackImage = asset('images/pet3.jpg');
@endphp

<div class="container mt-5 mb-5" style="max-width: 900px;">
    <div class="d-flex align-items-center gap-2 mb-4">
        <h3 class="fw-bold mb-0 text-uppercase" style="color: brown;">
            <i class="bi bi-bag-check-fill"></i> My Purchase History
        </h3>
    </div>

    @forelse($orders as $order)
        @php
            $orderItems = $order->items->filter(fn($item) => $item->item !== null);
            $orderTotal = $orderItems->sum(fn($item) => $item->price * $item->quantity);
            $createdAt = $order->created_at;
        @endphp

        <div class="card border-0 shadow-sm rounded-4 mb-4 p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3 flex-wrap gap-2">
                <div>
                    <span class="text-muted small d-block">Order Reference Number</span>
                    <strong class="text-dark font-monospace">#{{ $order->order_number }}</strong>
                </div>
                <div class="text-md-end">
                    <span class="text-muted small d-block">Placed On</span>
                    <small class="fw-bold text-secondary">{{ $createdAt->format('M d, Y - h:i A') }}</small>
                </div>
            </div>

            @foreach($orderItems->groupBy(fn($item) => $item->seller_id ?? 0) as $sellerId => $sellerItems)
                @php
                    $seller = $sellerItems->first()->seller;
                    $sellerName = $seller?->sellerApplication->store_name ?? $seller?->name ?? 'Unknown Seller';
                    $sellerTotal = $sellerItems->sum(fn($item) => $item->price * $item->quantity);
                @endphp

                <div class="mb-4 p-3 rounded-4 bg-light border">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                            <div class="d-flex align-items-center gap-3">
                                @php
                                    $sa = $seller?->sellerApplication ?? null;
                                @endphp
                                <img src="{{ $sa?->logo_url ?? asset('images/default-store.png') }}" alt="Store" style="width:48px;height:48px;object-fit:cover;border-radius:8px;border:1px solid #ddd;">
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $sellerName }}</h6>
                                    <small class="text-muted">Seller ID: {{ $sellerId ?: 'N/A' }}</small>
                                </div>
                            </div>
                        <div class="text-end">
                            <span class="badge bg-secondary text-uppercase">{{ $order->status ?? 'pending' }}</span>
                            <div class="text-muted small">Seller sub-total: ₱{{ number_format($sellerTotal, 2) }}</div>
                        </div>
                    </div>

                    @foreach($sellerItems as $item)
                        @php
                            $itemProduct = $item->item;
                            $itemStatus = strtolower($item->status ?? 'pending');
                        @endphp
                        <div class="d-flex align-items-center gap-3 py-3 border-top">
                               <img src="{{ $itemProduct?->image_url ?? $fallbackImage }}"
                                 alt="{{ $itemProduct?->name ?? 'Product Image' }}"
                                 class="rounded-3 border bg-white p-1"
                                 style="width: 70px; height: 70px; object-fit: contain;"
                                 onerror="this.src='{{ $fallbackImage }}'">

                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1 text-dark">{{ $itemProduct?->name ?? ($item->item_type ? class_basename($item->item_type) : 'Unknown item') }}</h6>
                                <small class="text-muted d-block">Qty: {{ $item->quantity }} • ₱{{ number_format($item->price, 2) }} each</small>
                                <small class="badge bg-{{ $itemStatus === 'delivered' ? 'success' : ($itemStatus === 'shipped' ? 'info' : 'warning') }} text-capitalize mt-2">{{ $itemStatus }}</small>
                            </div>

                            <div class="text-end">
                                <span class="fw-bold text-dark">₱{{ number_format($item->price * $item->quantity, 2) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach

            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                <div>
                    <span class="text-muted small">Current order status</span>
                    <div class="fw-bold text-dark text-uppercase">{{ $order->status ?? 'pending' }}</div>
                </div>
                <div class="text-end">
                    <span class="text-muted small d-block">Order Total</span>
                    <h5 class="fw-bold mb-0" style="color: brown;">₱{{ number_format($orderTotal, 2) }}</h5>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5 bg-white rounded-4 shadow-sm border">
            <i class="bi bi-bag-x text-muted mb-2 d-block" style="font-size: 3.5rem;"></i>
            <h5 class="fw-bold text-dark mb-1">No Orders Found</h5>
            <p class="text-muted small mb-3">You don't have any purchase records or tracked packages logged in this account layer.</p>
            <a href="/Home" class="btn btn-sm text-white rounded-pill px-4 fw-bold shadow-sm" style="background-color: brown;">
                Continue Browsing
            </a>
        </div>
    @endforelse
</div>

@endsection
