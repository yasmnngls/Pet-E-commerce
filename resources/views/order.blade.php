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
    
    @forelse($groupedOrders as $orderNumber => $items)
        @php 
            // Calculate the total order cost across all nested items inside this specific group
            $orderTotal = $items->sum(function($item) { return $item->price * $item->quantity; });
            // Read the main delivery pipeline status from the first item in the group
            $currentStatus = strtolower($items->first()->item_status ?? 'pending');
            $trackingNumber = $items->first()->tracking_number;
            $orderDate = $items->first()->order_date;
        @endphp

        <div class="card border-0 shadow-sm rounded-4 mb-4 p-4 bg-white">
            
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3 flex-wrap gap-2">
                <div>
                    <span class="text-muted small d-block">Order Reference Number</span>
                    <strong class="text-dark font-monospace">#{{ $orderNumber }}</strong>
                </div>
                <div class="text-md-end">
                    <span class="text-muted small d-block">Placed On</span>
                    <small class="fw-bold text-secondary">{{ \Carbon\Carbon::parse($orderDate)->format('M d, Y - h:i A') }}</small>
                </div>
            </div>

            <div class="row g-4">
                
                <div class="col-md-6 border-end border-light">
                    <div class="d-flex flex-column gap-3">
                        @foreach($items as $item)
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ asset($item->product_image ?? 'images/products/default.jpg') }}" 
                                     alt="Product Image" 
                                     class="rounded-3 border bg-light p-1" 
                                     style="width: 60px; height: 60px; object-fit: contain;"
                                     onerror="this.src='{{ $fallbackImage }}'">
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-0 text-dark small" style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        {{ $item->product_name ?? 'Item Missing SKU' }}
                                    </h6>
                                    <small class="text-muted d-block extra-small">
                                        Quantity: {{ $item->quantity }} • ₱{{ number_format($item->price, 2) }} each
                                    </small>
                                </div>
                                <div class="text-end">
                                    <span class="fw-bold text-dark small">₱{{ number_format($item->price * $item->quantity, 2) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-3 pt-3 border-top border-light d-flex justify-content-between align-items-center">
                        <span class="text-muted small fw-medium">Order Total:</span>
                        <h5 class="fw-bold mb-0" style="color: brown;">₱{{ number_format($orderTotal, 2) }}</h5>
                    </div>
                </div>

                <div class="col-md-6 d-flex flex-column justify-content-center px-lg-4">
                    <div class="d-flex justify-content-between text-center position-relative align-items-center mb-3">
                        
                        <div class="position-absolute top-50 start-0 end-0 translate-middle-y bg-secondary-subtle" style="height: 4px; z-index: 0;"></div>
                        
                        <div class="z-1 text-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2 shadow-sm timeline-node {{ in_array($currentStatus ?? '', ['pending', 'shipped', 'delivered']) ? 'timeline-active' : 'timeline-inactive' }}"
                                 style="width: 38px; height: 38px;">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <small class="fw-bold d-block text-dark tracking-step text-uppercase">Placed</small>
                        </div>

                        <div class="z-1 text-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2 shadow-sm timeline-node {{ in_array($currentStatus ?? '', ['shipped', 'delivered']) ? 'timeline-active' : 'timeline-inactive' }}"
                                 style="width: 38px; height: 38px;">
                                <i class="bi bi-truck"></i>
                            </div>
                            <small class="fw-bold d-block tracking-step text-uppercase {{ in_array($currentStatus, ['shipped', 'delivered']) ? 'text-dark' : 'text-muted' }}">Shipped</small>
                        </div>

                        <div class="z-1 text-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2 shadow-sm timeline-node {{ ($currentStatus ?? '') === 'delivered' ? 'timeline-delivered' : 'timeline-inactive' }}"
                                 style="width: 38px; height: 38px;">
                                <i class="bi bi-check2-circle"></i>
                            </div>
                            <small class="fw-bold d-block tracking-step text-uppercase {{ $currentStatus === 'delivered' ? 'text-success' : 'text-muted' }}">Delivered</small>
                        </div>

                    </div>

                    <div class="bg-light p-3 rounded-3 text-center border mt-2">
                        @if($currentStatus === 'pending')
                            <small class="text-muted"><i class="bi bi-info-circle-fill"></i> Your item is currently being processed and prepared by the merchant partner.</small>
                        @elseif($currentStatus === 'shipped')
                            <small class="text-dark fw-medium"><i class="bi bi-box-seam-fill text-primary"></i> Out for delivery! Courier Tracking code: <span class="font-monospace fw-bold text-danger">{{ $trackingNumber ?? 'Pending Assignment' }}</span></small>
                        @elseif($currentStatus === 'delivered')
                            <small class="text-success fw-bold"><i class="bi bi-shield-fill-check"></i> Package successfully handed over to recipient. Thank you for shopping!</small>
                        @else
                            <small class="text-muted">Current Order State Status: <span class="badge bg-secondary">{{ $currentStatus }}</span></small>
                        @endif
                    </div>
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

<style>
    .extra-small { font-size: 0.75rem; }
    .tracking-step { font-size: 0.7rem; letter-spacing: 0.5px; }
    .timeline-node { transition: all 0.3s ease-in-out; color: white; }
    .timeline-active { background-color: brown; }
    .timeline-inactive { background-color: #e4e5e9; color: #6c757d; }
    .timeline-delivered { background-color: #198754; }
</style>

@endsection