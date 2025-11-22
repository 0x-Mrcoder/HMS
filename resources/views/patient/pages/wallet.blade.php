@extends('layouts.patient')

@section('title', 'Wallet & Funding')

@section('content')
<div class="container-xxl py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">My Wallet</h4>
            <small class="text-muted">Fund your wallet via your static virtual account.</small>
        </div>
        <a href="{{ route('patient.portal.wallet.transactions') }}" class="btn btn-outline-secondary btn-sm"><i class="iconoir-doc-text me-1"></i>Full History</a>
    </div>
    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Current Balance</p>
                    <h2 class="mb-1">₦{{ number_format(optional($patient->wallet)->balance ?? 0, 2) }}</h2>
                    @if(!is_null($walletAlert) && $walletAlert > 0)
                        <span class="badge bg-danger-subtle text-danger">Top up ₦{{ number_format($walletAlert, 2) }} to reach minimum balance</span>
                    @else
                        <span class="badge bg-success-subtle text-success">Wallet is healthy</span>
                    @endif
                    <hr>
                    <p class="text-muted mb-1">Virtual Account (Sterling Bank)</p>
                    @if($patient->wallet?->virtual_account_number)
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <h4 class="mb-0">{{ $patient->wallet->virtual_account_number }}</h4>
                            <button class="btn btn-outline-secondary btn-sm" type="button" onclick="navigator.clipboard.writeText('{{ $patient->wallet->virtual_account_number }}')">Copy</button>
                        </div>
                        <small class="text-muted">Bank: Sterling Bank</small>
                        <p class="mt-3 mb-1 fw-semibold">How to fund</p>
                        <ul class="text-muted mb-0">
                            <li>Transfer to the virtual account number above (Sterling Bank).</li>
                            <li>Funds will reflect instantly after bank confirmation.</li>
                            <li>Use the transaction reference from your bank for support.</li>
                        </ul>
                    @else
                        <form method="POST" action="{{ route('patient.portal.wallet.generate') }}">
                            @csrf
                            <p class="text-muted">Generate your Sterling Bank virtual account to fund your wallet.</p>
                            <button class="btn btn-primary" type="submit">Generate Account Number</button>
                        </form>
                    @endif
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
