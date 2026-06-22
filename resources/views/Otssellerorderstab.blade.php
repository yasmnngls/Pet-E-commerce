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

    <div class="custom-card p-4">
        <h5 class="mb-4 font-weight-bold">Incoming Order Items</h5>
        
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-header">
                    <tr>
                        <th scope="col" class="ps-3">Item Image</th>
                        <th scope="col">Pet Category</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Buyer Address</th>
                        <th scope="col">Current Status</th>
                        <th scope="col" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="ps-3">
                            <div class="d-flex align-items-center gap-3">
                                <img src="https://via.placeholder.com/70" class="product-img-thum" alt="Squeaky Dog Bone">
                                <div>
                                    <span class="fw-bold d-block">Squeaky Dog Bone</span>
                                    <small class="text-muted d-block">Shop: Happy Paws Co.</small>
                                    <small class="text-primary fw-600">Buyer: Juan Dela Cruz</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-secondary px-2.5 py-1.5"><i class="fa-solid fa-bone me-1"></i> DOG</span></td>
                        <td class="fw-bold">2</td>
                        <td>
                            <div class="text-wrap" style="max-width: 250px;">
                                123 Maple St, Ortigas Center, Pasig City, Metro Manila
                            </div>
                        </td>
                        <td>
                            <select class="form-select form-select-sm border-dark status-dropdown" style="max-width: 150px; background-color: var(--ppp-bg-beige);" onchange="toggleTrackingField(this)">
                                <option value="Pending" selected>Pending</option>
                                <option value="Processing">Processing</option>
                                <option value="Shipped">Shipped</option>
                                <option value="Delivered">Delivered</option>
                            </select>
                            <div class="tracking-container mt-2 d-none">
                                <input type="text" class="form-control form-control-sm" placeholder="Tracking Number (e.g., UPS-99887766)">
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn btn-sm btn-ppp-red px-3 py-1.5 fw-bold" onclick="alert('Order status saved successfully!')">
                                    SAVE
                                </button>
                                <button class="btn btn-sm btn-outline-secondary px-3 py-1.5" onclick="alert('Order cancelled successfully!')">
                                    CANCEL
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="ps-3">
                            <div class="d-flex align-items-center gap-3">
                                <img src="https://via.placeholder.com/70" class="product-img-thum" alt="Slow Feeder Bowl">
                                <div>
                                    <span class="fw-bold d-block">Slow Feeder Bowl</span>
                                    <small class="text-muted d-block">Shop: Happy Paws Co.</small>
                                    <small class="text-primary fw-600">Buyer: Maria Santos</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-secondary px-2.5 py-1.5"><i class="fa-solid fa-fish me-1"></i> FISH</span></td>
                        <td class="fw-bold">1</td>
                        <td>
                            <div class="text-wrap" style="max-width: 250px;">
                                456 Oak Ave, Alabang, Muntinlupa City
                            </div>
                        </td>
                        <td>
                            <select class="form-select form-select-sm border-dark status-dropdown" style="max-width: 150px; background-color: var(--ppp-bg-beige);" onchange="toggleTrackingField(this)">
                                <option value="Pending">Pending</option>
                                <option value="Processing" selected>Processing</option>
                                <option value="Shipped">Shipped</option>
                                <option value="Delivered">Delivered</option>
                            </select>
                            <div class="tracking-container mt-2 d-none">
                                <input type="text" class="form-control form-control-sm" placeholder="Tracking Number">
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn btn-sm btn-ppp-red px-3 py-1.5 fw-bold" onclick="alert('Order status saved successfully!')">
                                    SAVE
                                </button>
                                <button class="btn btn-sm btn-outline-secondary px-3 py-1.5" onclick="alert('Order cancelled successfully!')">
                                    CANCEL
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="ps-3">
                            <div class="d-flex align-items-center gap-3">
                                <img src="https://via.placeholder.com/70" class="product-img-thum" alt="Bird Cage Mirror">
                                <div>
                                    <span class="fw-bold d-block">Bird Cage Mirror</span>
                                    <small class="text-muted d-block">Shop: Happy Paws Co.</small>
                                    <small class="text-primary fw-600">Buyer: Mark Aquino</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-secondary px-2.5 py-1.5"><i class="fa-solid fa-feather me-1"></i> BIRD</span></td>
                        <td class="fw-bold">1</td>
                        <td>
                            <div class="text-wrap" style="max-width: 250px;">
                                789 Pine Rd, Lahug, Cebu City, Cebu
                            </div>
                        </td>
                        <td>
                            <select class="form-select form-select-sm border-dark status-dropdown" style="max-width: 150px; background-color: var(--ppp-bg-beige);" onchange="toggleTrackingField(this)">
                                <option value="Pending">Pending</option>
                                <option value="Processing">Processing</option>
                                <option value="Shipped" selected>Shipped</option>
                                <option value="Delivered">Delivered</option>
                            </select>
                            <div class="tracking-container mt-2">
                                <label class="small text-muted d-block mb-1">Tracking Number</label>
                                <input type="text" class="form-control form-control-sm" value="UPS-99887766" placeholder="Tracking Number">
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn btn-sm btn-ppp-red px-3 py-1.5 fw-bold" onclick="alert('Order status saved successfully!')">
                                    SAVE
                                </button>
                                <button class="btn btn-sm btn-outline-secondary px-3 py-1.5" onclick="alert('Order cancelled successfully!')">
                                    CANCEL
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Show tracking input field conditionally if "Shipped" status option is picked
    function toggleTrackingField(selectElement) {
        const trackingContainer = selectElement.nextElementSibling;
        if (selectElement.value === 'Shipped') {
            trackingContainer.classList.remove('d-none');
        } else {
            trackingContainer.classList.add('d-none');
        }
    }

    // Initialize checking on load for pre-selected items
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.status-dropdown').forEach(dropdown => {
            toggleTrackingField(dropdown);
        });
    });
</script>
@endsection