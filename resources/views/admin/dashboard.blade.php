@extends('layouts.admin')

@section('title', 'Analytics Dashboard')

@section('content')
<div class="container-xxl">
    <div class="row g-3">
        <div class="col-md-3">
            <div class="card mini-widget">
                <div class="card-body">
                    <p class="text-muted mb-1">Total Patients</p>
                    <h3 class="mb-0">{{ number_format($metrics['total_patients']) }}</h3>
                    <span class="badge bg-info-subtle text-info mt-2">Registry</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mini-widget">
                <div class="card-body">
                    <p class="text-muted mb-1">Active Visits</p>
                    <h3 class="mb-0">{{ number_format($metrics['active_visits']) }}</h3>
                    <span class="badge bg-warning-subtle text-warning mt-2">OPD/IPD</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mini-widget">
                <div class="card-body">
                    <p class="text-muted mb-1">Wallet Float</p>
                    <h3 class="mb-0">₦{{ number_format($metrics['wallet_balance'], 2) }}</h3>
                    <span class="badge bg-success-subtle text-success mt-2">Patient Wallet</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mini-widget">
                <div class="card-body">
                    <p class="text-muted mb-1">Pending Visits</p>
                    <h3 class="mb-0">{{ number_format($metrics['pending_tests']) }}</h3>
                    <span class="badge bg-danger-subtle text-danger mt-2">Need attention</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-md-4">
            <div class="card mini-widget">
                <div class="card-body">
                    <p class="text-muted mb-1">Scheduled Surgeries</p>
                    <h3 class="mb-0">{{ number_format($metrics['scheduled_surgeries']) }}</h3>
                    <span class="badge bg-secondary-subtle text-secondary mt-2">Theatre</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mini-widget">
                <div class="card-body">
                    <p class="text-muted mb-1">Insurance Claims Pending</p>
                    <h3 class="mb-0">{{ number_format($metrics['pending_claims']) }}</h3>
                    <span class="badge bg-info-subtle text-info mt-2">NHIS desk</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mini-widget">
                <div class="card-body">
                    <p class="text-muted mb-1">Today's Income</p>
                    <h3 class="mb-0">₦{{ number_format($metrics['daily_income'], 2) }}</h3>
                    <span class="badge bg-success-subtle text-success mt-2">Accounts</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mb-0">Upcoming &amp; Recent Visits</h4>
                        <small class="text-muted">Consulting, Nursing and Diagnostics queue</small>
                    </div>
                    <a href="{{ route('visits.index') }}" class="btn btn-sm btn-soft-primary">View all</a>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Patient</th>
                                <th>Department</th>
                                <th>Status</th>
                                <th>Doctor</th>
                                <th>Schedule</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($upcomingVisits as $visit)
                                <tr>
                                    <td>{{ $visit->patient->full_name }}</td>
                                    <td>{{ $visit->department->name }}</td>
                                    <td><span class="badge bg-secondary-subtle text-capitalize">{{ str_replace('_', ' ', $visit->status) }}</span></td>
                                    <td>{{ $visit->doctor_name ?? '—' }}</td>
                                    <td>{{ $visit->scheduled_at?->format('d M, h:ia') ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No visits logged.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Wallet Alerts</h5>
                    <span class="badge bg-danger-subtle text-danger">{{ $lowBalanceWallets->count() }} critical</span>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @forelse ($lowBalanceWallets as $wallet)
                            <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <div>
                                    <h6 class="mb-0">{{ $wallet->patient->full_name }}</h6>
                                    <small class="text-muted">{{ $wallet->patient->hospital_id }}</small>
                                </div>
                                <span class="fw-semibold text-danger">₦{{ number_format($wallet->balance, 2) }}</span>
                            </li>
                        @empty
                            <li class="text-muted text-center py-3">All patients are funded</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Department Load</h5>
                    <span class="text-muted small">OPD + Diagnostics</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach ($departments as $department)
                            <div class="col-md-6">
                                <div class="border rounded p-3 h-100">
                                    <p class="mb-1 fw-semibold">{{ $department->name }}</p>
                                    <h4 class="mb-0">{{ $department->visits_count }} visits</h4>
                                    <small class="text-muted">{{ $department->services->count() }} services configured</small>
                                </div>
                            </div>
                        @endforeach
                        @if ($departments->isEmpty())
                            <div class="col-12 text-center text-muted">No departments configured yet.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Wallet Activity</h5>
                    <a href="{{ route('transactions.index') }}" class="btn btn-soft-secondary btn-sm">All activity</a>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse ($recentTransactions as $transaction)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <p class="mb-0 fw-semibold">{{ $transaction->wallet->patient->full_name }}</p>
                                        <small class="text-muted text-capitalize">{{ $transaction->transaction_type }} · {{ $transaction->transacted_at->diffForHumans() }}</small>
                                    </div>
                                    <span class="fw-semibold {{ $transaction->transaction_type === 'deduction' ? 'text-danger' : 'text-success' }}">₦{{ number_format($transaction->amount, 2) }}</span>
                                </div>
                                <small class="text-muted">{{ $transaction->service ?? 'General service' }}</small>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">No wallet movement yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Theatre Queue</h5>
                    <a href="{{ route('theatre.surgeries.index') }}" class="btn btn-soft-secondary btn-sm">Manage</a>
                </div>
                <div class="list-group list-group-flush">
                    @forelse ($recentSurgeries as $surgery)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-0 fw-semibold">{{ $surgery->procedure_name }}</p>
                                <small class="text-muted">{{ $surgery->patient->full_name }} · {{ $surgery->scheduled_at?->format('d M, h:ia') ?? 'TBD' }}</small>
                            </div>
                            <span class="badge bg-secondary-subtle text-capitalize">{{ str_replace('_', ' ', $surgery->status) }}</span>
                        </div>
                    @empty
                        <div class="list-group-item text-center text-muted">No surgical procedures logged.</div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Insurance Pipeline</h5>
                    <a href="{{ route('insurance.claims.index') }}" class="btn btn-soft-secondary btn-sm">All claims</a>
                </div>
                <div class="list-group list-group-flush">
                    @forelse ($openClaims as $claim)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-0 fw-semibold">{{ $claim->patient->full_name }} · {{ $claim->policy_number }}</p>
                                <small class="text-muted">{{ $claim->provider }} &middot; Submitted {{ $claim->submitted_at?->diffForHumans() ?? 'N/A' }}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-secondary-subtle text-capitalize">{{ $claim->claim_status }}</span>
                                <p class="mb-0 mt-1 fw-semibold">₦{{ number_format($claim->total_amount, 2) }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item text-center text-muted">No claims awaiting approval.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Collections by Channel</h5>
                    <a href="{{ route('accounts.index') }}" class="btn btn-soft-secondary btn-sm">Accounts</a>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @forelse ($channelSplit as $channel => $total)
                            <li class="d-flex justify-content-between mb-1">
                                <span class="text-uppercase">{{ $channel }}</span>
                                <strong>₦{{ number_format($total, 2) }}</strong>
                            </li>
                        @empty
                            <li class="text-muted text-center">No records captured.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
