@extends('layouts.admin')

@section('title', $patient->full_name)

@section('content')
<div class="container-xxl">
    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <span class="thumb-xl bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fs-3 me-3">
                            {{ strtoupper(substr($patient->first_name, 0, 1)) }}
                        </span>
                        <div>
                            <h4 class="mb-0">{{ $patient->full_name }}</h4>
                            <small class="text-muted">{{ strtoupper($patient->hospital_id) }}</small>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Phone</span>
                        <span>{{ $patient->phone }}</span>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Gender</span>
                        <span class="text-capitalize">{{ $patient->gender }}</span>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>State</span>
                        <span>{{ $patient->state }}</span>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>NHIS</span>
                        <span>{{ $patient->nhis_number ?? 'Not Enrolled' }}</span>
                    </div>
                    <div class="mt-3">
                        <p class="text-muted mb-1">Address</p>
                        <p class="mb-0">{{ $patient->address }}, {{ $patient->city }}, {{ $patient->state }}</p>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Wallet</h5>
                    <a href="{{ route('wallets.show', $patient->wallet) }}" class="btn btn-soft-primary btn-sm">Open Wallet</a>
                </div>
                <div class="card-body">
                    <h2 class="fw-bold">₦{{ number_format($patient->wallet?->balance ?? 0, 2) }}</h2>
                    <p class="text-muted mb-0">Low balance threshold: ₦{{ number_format($patient->wallet?->low_balance_threshold ?? 0, 2) }}</p>
                    <hr>
                    <p class="text-muted fw-semibold">Recent transactions</p>
                    <ul class="list-unstyled mb-0">
                        @forelse ($patient->wallet?->transactions->take(5) as $transaction)
                            <li class="d-flex justify-content-between py-1">
                                <span>{{ ucfirst($transaction->transaction_type) }} · <small class="text-muted">{{ $transaction->transacted_at->format('d M, h:ia') }}</small></span>
                                <span class="fw-semibold">₦{{ number_format($transaction->amount, 2) }}</span>
                            </li>
                        @empty
                            <li class="text-muted">No wallet history</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Clinical Visits</h5>
                    <span class="badge bg-primary-subtle text-primary">{{ $patient->visits->count() }} visits</span>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Department</th>
                                <th>Doctor</th>
                                <th>Status</th>
                                <th>Type</th>
                                <th>Scheduled</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($patient->visits as $visit)
                                <tr>
                                    <td>{{ $visit->department->name }}</td>
                                    <td>{{ $visit->doctor_name ?? '—' }}</td>
                                    <td><span class="badge bg-secondary-subtle text-capitalize">{{ str_replace('_', ' ', $visit->status) }}</span></td>
                                    <td class="text-uppercase">{{ $visit->visit_type }}</td>
                                    <td>{{ $visit->scheduled_at?->format('d M, h:ia') ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No visits captured yet.</td>
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
