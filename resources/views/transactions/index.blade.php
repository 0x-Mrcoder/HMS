@extends('layouts.admin')

@section('title', 'Wallet Transactions')

@section('content')
<div class="container-xxl">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mb-0">Financial Activity</h4>
                        <small class="text-muted">Wallet inflows/outflows across the facility</small>
                    </div>
                    <form method="GET" class="d-inline-flex">
                        <select name="type" class="form-select" onchange="this.form.submit()">
                            <option value="">All Transactions</option>
                            @foreach(['deposit' => 'Deposits', 'deduction' => 'Charges', 'refund' => 'Refunds'] as $value => $label)
                                <option value="{{ $value }}" @selected($type === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Patient</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Service</th>
                                <th>Performed By</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->wallet->patient->full_name }}</td>
                                    <td class="text-capitalize">{{ $transaction->transaction_type }}</td>
                                    <td class="fw-semibold">₦{{ number_format($transaction->amount, 2) }}</td>
                                    <td class="text-uppercase">{{ $transaction->payment_method }}</td>
                                    <td>{{ $transaction->service ?? '—' }}</td>
                                    <td>{{ $transaction->performed_by ?? 'System' }}</td>
                                    <td>{{ $transaction->transacted_at->format('d M, h:ia') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">No transactions logged.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
