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

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="p-3 border rounded bg-white shadow-sm d-flex align-items-center gap-3">
                    <div class="fs-1 text-muted"><i class="fa-solid fa-coins"></i></div>
                    <div>
                        <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 0.75rem;">Total Revenue</small>
                        <h4 class="fw-bold m-0">₱{{ number_format($totalRevenue, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 border rounded bg-white shadow-sm d-flex align-items-center gap-3" style="border-left: 4px solid green !important;">
                    <div class="fs-1 text-success"><i class="fa-solid fa-money-bill-transfer"></i></div>
                    <div>
                        <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 0.75rem;">Available Balance</small>
                        <h4 class="fw-bold m-0">₱{{ number_format($availableBalance, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 border rounded bg-white shadow-sm d-flex align-items-center gap-3">
                    <div class="fs-1 text-warning"><i class="fa-solid fa-clock-rotate-left"></i></div>
                    <div>
                        <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 0.75rem;">Pending Payments</small>
                        <h4 class="fw-bold m-0">₱{{ number_format($pendingPayments, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 border rounded bg-white shadow-sm d-flex align-items-center gap-3">
                    <div class="fs-1 text-primary"><i class="fa-solid fa-box-open"></i></div>
                    <div>
                        <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 0.75rem;">Orders Fulfilled</small>
                        <h4 class="fw-bold m-0">{{ $fulfilledCount }}</h4>
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
                            <th scope="col">Order Reference ID</th>
                            <th scope="col">Product Name</th>
                            <th scope="col">Earnings (PHP)</th>
                            <th scope="col">Fulfillment Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $tx)
                            <tr>
                                <td class="ps-3">{{ date('Y-m-d', strtotime($tx->date)) }}</td>
                                <td class="fw-bold text-muted">#{{ $tx->order_number }}</td>
                                <td class="fw-bold">{{ $tx->quantity }}x {{ $tx->product_name }}</td>
                                <td class="fw-bold">₱{{ number_format($tx->earnings, 2) }}</td>
                                <td>
                                    <span class="badge {{ $tx->status == 'delivered' ? 'bg-success' : 'bg-warning text-dark' }} px-2.5 py-1.5 text-uppercase">
                                        {{ $tx->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted fw-bold">No historic balance entries compiled yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="withdrawal-pane" role="tabpanel">
        <div class="d-flex justify-content-between align-items-center flex-wrap flex-md-nowrap pb-3 mb-4 border-bottom">
            <div class="d-flex align-items-center gap-2">
                <a href="#" class="text-decoration-none fw-bold" style="color: var(--ppp-red);" onclick="switchState('overview-pane')">
                    <i class="fa-solid fa-chevron-left"></i> Back to Dashboard
                </a>
                <span class="text-muted">|</span>
                <span class="h5 m-0 font-weight-bold text-dark">Request Withdrawal</span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2"></i>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('seller.earnings.withdraw') }}" method="POST">
            @csrf
            <div class="custom-card p-4">
                <h5 class="mb-4 font-weight-bold">Withdraw Funds</h5>
                
                <div class="row g-4 align-items-center">
                    <div class="col-md-3 text-center">
                        <div class="p-4 rounded border text-center shadow-sm" style="background-color: rgba(114, 28, 36, 0.04);">
                            <small class="text-muted d-block font-weight-bold mb-1 text-uppercase" style="font-size: 0.7rem;">Withdrawable Balance</small>
                            <h3 class="fw-bold text-dark m-0">₱{{ number_format($availableBalance, 2) }}</h3>
                        </div>
                    </div>

                    <div class="col-md-9">
                        <div class="mb-3 row align-items-center">
                            <label class="col-sm-4 col-form-label fw-bold">Enter Withdrawal Amount</label>
                            <div class="col-sm-8">
                                <input type="number" name="amount" class="form-control border-dark" id="withdrawAmount" value="{{ min($availableBalance, 100000) }}" min="100" max="100000" step="0.01" required oninput="calculateTransactionSummary()">
                                <small class="text-muted">Minimum ₱100.00, maximum ₱100,000.00 per withdrawal request.</small>
                            </div>
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label class="col-sm-4 col-form-label fw-bold">Select Withdrawal Method</label>
                            <div class="col-sm-8">
                                <select name="withdrawal_method" class="form-select border-dark" required>
                                    <option value="Manual Check" {{ empty($bankInfo) ? 'selected' : '' }}>Manual Check</option>
                                    @if($bankInfo && $bankInfo->bank_name)
                                        <option value="Bank Transfer" selected>Bank Transfer ({{ $bankInfo->bank_name }})</option>
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label class="col-sm-4 col-form-label fw-bold">Bank Name</label>
                            <div class="col-sm-8">
                                <input type="text" name="bank_name" class="form-control border-dark" value="{{ $bankInfo->bank_name ?? '' }}" placeholder="Bank name">
                            </div>
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label class="col-sm-4 col-form-label fw-bold">Account Number</label>
                            <div class="col-sm-8">
                                <input type="text" name="bank_account_number" class="form-control border-dark" value="{{ $bankInfo->bank_account_number ?? '' }}" placeholder="Account number">
                            </div>
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label class="col-sm-4 col-form-label fw-bold">Account Holder</label>
                            <div class="col-sm-8">
                                <input type="text" name="bank_account_holder" class="form-control border-dark" value="{{ $bankInfo->bank_account_holder ?? '' }}" placeholder="Account holder name">
                            </div>
                        </div>

                        <div class="p-3 bg-light rounded border border-secondary-subtle mb-4" style="max-width: 500px; margin-left: auto;">
                            <h6 class="fw-bold border-bottom pb-2 mb-2">Transaction Summary</h6>
                            <div class="d-flex justify-content-between my-1">
                                <span class="text-muted">Withdrawal Amount:</span>
                                <span class="fw-bold" id="sumAmount">₱0.00</span>
                            </div>
                            <div class="d-flex justify-content-between my-1">
                                <span class="text-muted">Transaction Fee:</span>
                                <span class="fw-bold text-danger" id="sumFee">₱50.00</span>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between my-1 fs-5">
                                <span class="fw-bold">Net Withdrawal:</span>
                                <span class="fw-bold text-success" id="sumNet">₱0.00</span>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-ppp-red btn-lg px-5 fw-bold">CONFIRM WITHDRAWAL</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="custom-card p-4 mt-4">
            <h5 class="mb-4 font-weight-bold">Withdrawal History</h5>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-header">
                        <tr>
                            <th scope="col" class="ps-3">Requested At</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Method</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($withdrawals as $withdrawal)
                            <tr>
                                <td class="ps-3">{{ 
                                    
                                    \Carbon\Carbon::parse($withdrawal->requested_at)->format('Y-m-d H:i')
                                }}</td>
                                <td class="fw-bold">₱{{ number_format($withdrawal->amount, 2) }}</td>
                                <td>{{ $withdrawal->withdrawal_method }}</td>
                                <td>
                                    <span class="badge {{ $withdrawal->status === 'completed' ? 'bg-success' : ($withdrawal->status === 'processing' ? 'bg-warning text-dark' : 'bg-secondary') }} px-2.5 py-1.5 text-uppercase">
                                        {{ $withdrawal->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted fw-bold">No withdrawal requests have been logged yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // State layout visual switcher toggle
    function switchState(paneId) {
        document.querySelectorAll('.tab-pane').forEach(pane => {
            pane.classList.remove('show', 'active');
        });
        document.getElementById(paneId).classList.add('show', 'active');
    }

    function calculateTransactionSummary() {
        const amountInput = document.getElementById('withdrawAmount').value;
        const amount = parseFloat(amountInput) || 0;
        const fee = amount > 0 ? 50.00 : 0.00;
        const net = Math.max(0, amount - fee);

        document.getElementById('sumAmount').innerText = '₱' + amount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('sumFee').innerText = '₱' + fee.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('sumNet').innerText = '₱' + net.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
    
    // Auto execute on page bootup run 
    document.addEventListener("DOMContentLoaded", function() {
        calculateTransactionSummary();
    });
</script>
@endsection