@extends('Otssellertabslayout')

@section('content')
<div class="tab-content" id="earningsTabContent">
    
    <div class="tab-pane fade show active" id="overview-pane" role="tabpanel">
        <div class="d-flex justify-content-between align-items-center flex-wrap flex-md-nowrap pb-3 mb-4 border-bottom">
            <h1 class="h3 font-weight-bold">Seller Earnings</h1>
            <button class="btn btn-ppp-red py-2 px-4 fw-bold" onclick="switchState('withdrawal-pane')">
                REQUEST WITHDRAWAL
            </button>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="p-3 border rounded bg-white shadow-sm d-flex align-items-center gap-3">
                    <div class="fs-1 text-muted"><i class="fa-solid fa-coins"></i></div>
                    <div>
                        <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 0.75rem;">Total Revenue</small>
                        <h4 class="fw-bold m-0">₱12,500.00</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 border rounded bg-white shadow-sm d-flex align-items-center gap-3" style="border-left: 4px solid green !important;">
                    <div class="fs-1 text-success"><i class="fa-solid fa-money-bill-transfer"></i></div>
                    <div>
                        <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 0.75rem;">Available Balance</small>
                        <h4 class="fw-bold m-0">₱8,750.00</h4>
                        <span class="badge bg-success-subtle text-success p-1 rounded" style="font-size: 0.65rem;"><i class="fa-solid fa-circle-check"></i> Withdrawable</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 border rounded bg-white shadow-sm d-flex align-items-center gap-3">
                    <div class="fs-1 text-warning"><i class="fa-solid fa-clock-rotate-left"></i></div>
                    <div>
                        <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 0.75rem;">Pending Payments</small>
                        <h4 class="fw-bold m-0">₱3,750.00</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 border rounded bg-white shadow-sm d-flex align-items-center gap-3">
                    <div class="fs-1 text-primary"><i class="fa-solid fa-box-open"></i></div>
                    <div>
                        <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 0.75rem;">Total Orders Fulfilled</small>
                        <h4 class="fw-bold m-0">148</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="custom-card p-4">
            <h5 class="mb-4 font-weight-bold">Recent Transaction History</h5>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-header">
                        <tr>
                            <th scope="col" class="ps-3">Date</th>
                            <th scope="col">Transaction ID</th>
                            <th scope="col">Product Name</th>
                            <th scope="col">Order ID</th>
                            <th scope="col">Earnings (PHP)</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="ps-3">2026-06-21</td>
                            <td class="fw-bold text-muted">TX-PP-501</td>
                            <td class="fw-bold">1x Squeaky Dog Bone</td>
                            <td>#987654</td>
                            <td class="fw-bold">₱250.00</td>
                            <td><span class="badge bg-success text-white px-2.5 py-1.5 text-uppercase">Paid</span></td>
                        </tr>
                        <tr>
                            <td class="ps-3">2026-06-20</td>
                            <td class="fw-bold text-muted">TX-PP-498</td>
                            <td class="fw-bold">3x Slow Feeder Bowl</td>
                            <td>#987653</td>
                            <td class="fw-bold">₱1,499.97</td>
                            <td><span class="badge bg-success text-white px-2.5 py-1.5 text-uppercase">Paid</span></td>
                        </tr>
                        <tr>
                            <td class="ps-3">2026-06-19</td>
                            <td class="fw-bold text-muted">TX-PP-495</td>
                            <td class="fw-bold">1x Oatmeal Soothing Shampoo</td>
                            <td>#987652</td>
                            <td class="fw-bold">₱320.00</td>
                            <td><span class="badge bg-warning text-dark px-2.5 py-1.5 text-uppercase">Pending</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="withdrawal-pane" role="tabpanel">
        <div class="d-flex justify-content-between align-items-center flex-wrap flex-md-nowrap pb-3 mb-4 border-bottom">
            <div class="d-flex align-items-center gap-2">
                <a href="#" class="text-decoration-none fw-bold" style="color: var(--ppp-red);" onclick="switchState('overview-pane')">
                    <i class="fa-solid fa-chevron-left"></i> Back to Earnings Dashboard
                </a>
                <span class="text-muted">|</span>
                <span class="h5 m-0 font-weight-bold text-dark">Request Withdrawal</span>
            </div>
            <button class="btn btn-outline-secondary py-2 px-4 fw-bold" onclick="switchState('overview-pane')">
                Cancel Withdrawal Request
            </button>
        </div>

        <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Withdrawal request submitted for processing!'); switchState('overview-pane');">
            @csrf
            <div class="custom-card p-4">
                <h5 class="mb-4 font-weight-bold">Withdraw Funds</h5>
                
                <div class="row g-4 align-items-center">
                    <div class="col-md-3 text-center">
                        <div class="p-4 rounded border text-center shadow-sm" style="background-color: rgba(114, 28, 36, 0.04);">
                            <small class="text-muted d-block font-weight-bold mb-1 text-uppercase" style="font-size: 0.7rem;">Withdrawal Balance</small>
                            <h3 class="fw-bold text-dark m-0">₱8,750.00</h3>
                        </div>
                    </div>

                    <div class="col-md-9">
                        <div class="mb-3 row align-items-center">
                            <label class="col-sm-4 col-form-label fw-bold">Enter Withdrawal Amount</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control border-dark" id="withdrawAmount" value="8750.00" min="100" max="8750" step="0.01" required oninput="calculateTransactionSummary()">
                                <small class="text-muted">Minimum ₱100.00 | Maximum ₱8,750.00</small>
                            </div>
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label class="col-sm-4 col-form-label fw-bold">Select Withdrawal Method</label>
                            <div class="col-sm-8">
                                <select class="form-select border-dark" required>
                                    <option value="BDO" selected>BDO Unibank (**** 1234)</option>
                                    <option value="BPI">BPI Express (**** 5678)</option>
                                    <option value="GCASH">GCash Mobile Wallet (0917***4321)</option>
                                </select>
                            </div>
                        </div>

                        <div class="p-3 bg-light rounded border border-secondary-subtle mb-4" style="max-width: 500px; margin-left: auto;">
                            <h6 class="fw-bold border-bottom pb-2 mb-2">Transaction Summary</h6>
                            <div class="d-flex justify-content-between my-1">
                                <span class="text-muted">Withdrawal Amount:</span>
                                <span class="fw-bold" id="sumAmount">₱8,750.00</span>
                            </div>
                            <div class="d-flex justify-content-between my-1">
                                <span class="text-muted">Transaction Fee:</span>
                                <span class="fw-bold text-danger" id="sumFee">₱50.00</span>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between my-1 fs-5">
                                <span class="fw-bold">Net Withdrawal:</span>
                                <span class="fw-bold text-success" id="sumNet">₱8,700.00</span>
                            </div>
                            <small class="text-muted d-block mt-2 italic"><i class="fa-solid fa-circle-info"></i> Withdrawals are processed within 2-3 business days.</small>
                        </div>

                        <div class="text-end">
                            <span class="me-3 small text-muted">Need to add a new withdrawal method? <a href="#" class="text-dark fw-bold">Click here.</a></span>
                            <button type="submit" class="btn btn-ppp-red btn-lg px-5 fw-bold">CONFIRM WITHDRAWAL</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

</div>
@endsection

@section('scripts')
<script>
    // State controller toggle logic switching 
    function switchState(paneId) {
        // Deactivate active states cleanly
        document.querySelectorAll('.tab-pane').forEach(pane => {
            pane.classList.remove('show', 'active');
        });
        // Engage target tab viewport module overlay
        const targetPane = document.getElementById(paneId);
        targetPane.classList.add('show', 'active');
    }

    // Dynamic Full Stack Calculation Engine for Withdrawal Fees
    function calculateTransactionSummary() {
        const amountInput = document.getElementById('withdrawAmount').value;
        const amount = parseFloat(amountInput) || 0;
        const fee = amount > 0 ? 50.00 : 0.00; // Flat fee structure matching image design specifications
        const net = Math.max(0, amount - fee);

        document.getElementById('sumAmount').innerText = '₱' + amount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('sumFee').innerText = '₱' + fee.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('sumNet').innerText = '₱' + net.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
</script>
@endsection