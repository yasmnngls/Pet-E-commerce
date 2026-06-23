@extends('Otssellertabslayout')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap flex-md-nowrap pb-3 mb-4 border-bottom">
        <h1 class="h3 font-weight-bold">Seller Order Management Dashboard</h1>
        <div class="d-flex gap-3 align-items-center">
            <div class="input-group" style="width: 300px;">
                <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                <input type="text" class="form-control border-start-0" placeholder="Search orders or buyers...">
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="custom-card p-4">
        <h5 class="mb-4 font-weight-bold">Incoming Order Items</h5>
        
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-header">
                    <tr>
                        <th scope="col" class="ps-3">Item Details</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Buyer Address</th>
                        <th scope="col">Fulfillment Status</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        @php
                            $orderImageUrl = $order->product_image
                                ? (file_exists(storage_path('app/public/' . $order->product_image))
                                    ? asset('storage/' . $order->product_image)
                                    : (file_exists(public_path($order->product_image)) ? asset($order->product_image) : 'https://via.placeholder.com/70'))
                                : 'https://via.placeholder.com/70';
                        @endphp
                        <tr>
                            <td class="ps-3">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $orderImageUrl }}" class="product-img-thum" alt="Product Image">
                                    <div>
                                        <span class="fw-bold d-block">{{ $order->product_name }}</span>
                                        <small class="text-muted d-block">Order Ref: #{{ $order->order_number }}</small>
                                        <small class="text-primary fw-bold d-block">Buyer: {{ $order->buyer_name }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="fw-bold">{{ $order->quantity }}</td>
                            <td>
                                <div class="text-wrap" style="max-width: 250px; font-size: 0.9rem;">
                                    {{ $order->street }}, {{ $order->barangay }}, {{ $order->city }}, {{ $order->province }}
                                </div>
                            </td>
                            <td>
                                <form action="{{ route('seller.orders.update', $order->order_item_id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="form-select form-select-sm border-dark status-dropdown" style="max-width: 150px; background-color: var(--ppp-bg-beige);" onchange="toggleTrackingField(this)">
                                        <option value="pending" {{ $order->item_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="shipped" {{ $order->item_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                        <option value="delivered" {{ $order->item_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    </select>

                                    <div class="tracking-container mt-2 {{ $order->item_status == 'shipped' ? '' : 'd-none' }}">
                                        <input type="text" name="tracking_number" class="form-control form-control-sm" value="{{ $order->tracking_number }}" placeholder="Tracking Code">
                                    </div>

                                    <button type="submit" class="btn btn-sm btn-ppp-red mt-2 px-3 py-1.5 fw-bold">
                                        SAVE/UPDATE
                                    </button>
                                </form>
                            </td>
                            <td class="text-center">
                                <form action="{{ route('seller.orders.cancel', $order->order_item_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this order item?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger px-3 py-1.5 fw-bold">CANCEL</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted fw-bold">No incoming orders found in your queue.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function toggleTrackingField(selectElement) {
        const trackingContainer = selectElement.nextElementSibling;
        if (selectElement.value === 'shipped') {
            trackingContainer.classList.remove('d-none');
        } else {
            trackingContainer.classList.add('d-none');
        }
    }
</script>
@endsection