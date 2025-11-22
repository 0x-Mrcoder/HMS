@extends('layouts.patient')

@section('title', 'Wallet Transactions')

@section('content')
<div class="container-xxl py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Wallet Transactions</h4>
            <small class="text-muted">History for {{ $patient->full_name }} ({{ $patient->hospital_id }})</small>
        </div>
        <a href="{{ route('patient.portal.dashboard') }}#wallet" class="btn btn-outline-secondary btn-sm">Back to dashboard</a>
    </div>
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Type</th>
                            <th>Method</th>
                            <th>Service</th>
                            <th>Amount</th>
                            <th>Balance After</th>
                            <th>Reference</th>
                            <th>When</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $txn)
                            <tr>
                                <td><span class="badge bg-secondary-subtle text-capitalize">{{ $txn->transaction_type }}</span></td>
                                <td>{{ strtoupper($txn->payment_method ?? '--') }}</td>
                                <td>{{ $txn->service ?? 'N/A' }}</td>
                                <td class="{{ $txn->transaction_type === 'deduction' ? 'text-danger' : 'text-success' }}">₦{{ number_format($txn->amount, 2) }}</td>
                                <td>₦{{ number_format($txn->balance_after, 2) }}</td>
                                <td>{{ $txn->reference ?? '--' }}</td>
                                <td>{{ $txn->transacted_at?->format('d M Y, h:ia') ?? $txn->created_at->format('d M Y, h:ia') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No transactions yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
