@extends('layouts.admin')

@section('title', 'Wallet - ' . $wallet->patient->full_name)

@section('content')
<div class="container-xxl">
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <p class="text-uppercase text-muted mb-1">Patient</p>
                    <h4>{{ $wallet->patient->full_name }}</h4>
                    <p class="text-muted">{{ $wallet->patient->hospital_id }}</p>
                    <hr>
                    <p class="text-uppercase text-muted mb-1">Available Balance</p>
                    <h2 class="fw-bold">₦{{ number_format($wallet->balance, 2) }}</h2>
                    <p class="text-muted mb-0">Low balance alert at ₦{{ number_format($wallet->low_balance_threshold, 2) }}</p>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Update Wallet</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('wallets.transactions.store', $wallet) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Transaction Type</label>
                            <select name="transaction_type" class="form-select" required>
                                <option value="deposit">Deposit</option>
                                <option value="deduction">Deduction</option>
                                <option value="refund">Refund</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Payment Method</label>
                            <select name="payment_method" class="form-select">
                                <option value="cash">Cash</option>
                                <option value="pos">POS</option>
                                <option value="transfer">Bank Transfer</option>
                                <option value="online">Online</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Amount (₦)</label>
                            <input type="number" step="0.01" name="amount" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Service (Optional)</label>
                            <input type="text" name="service" class="form-control" placeholder="Consultation, Pharmacy, etc">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Narration</label>
                            <textarea name="description" class="form-control" rows="2"></textarea>
                        </div>
                        <button class="btn btn-primary w-100" type="submit">Post Transaction</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Wallet Statement</h5>
                    <span class="badge bg-secondary-subtle text-secondary">{{ $wallet->transactions->count() }} entries</span>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Reference</th>
                                <th>Type</th>
                                <th>Method</th>
                                <th>Amount</th>
                                <th>Balance After</th>
                                <th>When</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($wallet->transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->reference ?? '—' }}</td>
                                    <td class="text-capitalize">{{ $transaction->transaction_type }}</td>
                                    <td class="text-uppercase">{{ $transaction->payment_method }}</td>
                                    <td class="fw-semibold">₦{{ number_format($transaction->amount, 2) }}</td>
                                    <td>₦{{ number_format($transaction->balance_after, 2) }}</td>
                                    <td>{{ $transaction->transacted_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No transactions yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
