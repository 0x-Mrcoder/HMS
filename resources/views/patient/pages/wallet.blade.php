@extends('layouts.patient')

@section('title', 'Wallet & Funding')

@section('content')
<div class="container-xxl py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Wallet &amp; Funding</h4>
            <small class="text-muted">Manage your wallet balance and statements.</small>
        </div>
        <a href="{{ route('patient.portal.wallet.transactions') }}" class="btn btn-outline-secondary btn-sm"><i class="iconoir-doc-text me-1"></i>Full History</a>
    </div>
    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <p class="text-muted mb-1">Current Balance</p>
                            <h2 class="mb-0">₦{{ number_format(optional($patient->wallet)->balance ?? 0, 2) }}</h2>
                            @if($patient->wallet?->virtual_account_number)
                                <p class="mb-0 small text-muted">Virtual Account: <strong>{{ $patient->wallet->virtual_account_number }}</strong></p>
                            @endif
                            @if(!is_null($walletAlert) && $walletAlert > 0)
                                <span class="badge bg-danger-subtle text-danger mt-1">Top up ₦{{ number_format($walletAlert, 2) }} to reach minimum balance</span>
                            @else
                                <span class="badge bg-success-subtle text-success mt-1">Wallet is healthy</span>
                            @endif
                        </div>
                        <div class="text-end">
                            <p class="mb-1 text-muted small">Hospital ID</p>
                            <p class="fw-semibold mb-0">{{ $patient->hospital_id }}</p>
                        </div>
                    </div>
                    <div class="border rounded p-3">
                        <form class="row g-2 align-items-end" method="POST" action="{{ route('patient.portal.wallet.deposit') }}">
                            @csrf
                            <div class="col-md-5">
                                <label class="form-label mb-1">Amount (₦)</label>
                                <input type="number" name="amount" step="0.01" min="0.01" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label mb-1">Payment Method</label>
                                <select name="payment_method" class="form-select" required>
                                    <option value="cash">Cash</option>
                                    <option value="pos">POS</option>
                                    <option value="transfer">Transfer</option>
                                    <option value="online">Online</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mb-1">Reference</label>
                                <input type="text" name="reference" class="form-control" placeholder="Optional">
                            </div>
                            <div class="col-12 d-grid">
                                <button class="btn btn-primary" type="submit"><i class="iconoir-wallet me-1"></i>Add Funds</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Recent Activity</h6>
                    <small class="text-muted">{{ $transactions->count() }} shown</small>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @forelse ($transactions as $transaction)
                            <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <div>
                                    <p class="mb-0 fw-semibold">{{ $transaction->service ?? ucfirst($transaction->transaction_type) }}</p>
                                    <small class="text-muted">{{ $transaction->transacted_at?->diffForHumans() }}</small>
                                </div>
                                <span class="fw-semibold {{ $transaction->transaction_type === 'deduction' ? 'text-danger' : 'text-success' }}">₦{{ number_format($transaction->amount, 2) }}</span>
                            </li>
                        @empty
                            <li class="text-center text-muted py-4">No wallet activity yet.</li>
                        @endforelse
                    </ul>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('patient.portal.wallet.transactions') }}" class="btn btn-soft-secondary btn-sm">View all</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
