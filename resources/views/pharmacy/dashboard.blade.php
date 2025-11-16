@extends('layouts.pharmacy')

@section('title', 'Pharmacy Fulfilment')

@section('content')
<div class="container-xxl py-4" id="overview">
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card mini-widget">
                <div class="card-body">
                    <p class="text-muted mb-1">Pending Orders</p>
                    <h3 class="mb-0">{{ number_format($metrics['pending_orders']) }}</h3>
                    <span class="badge bg-warning-subtle text-warning mt-2">Awaiting dispense</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mini-widget">
                <div class="card-body">
                    <p class="text-muted mb-1">Dispensed Today</p>
                    <h3 class="mb-0">{{ number_format($metrics['dispensed_today']) }}</h3>
                    <span class="badge bg-success-subtle text-success mt-2">Completed</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mini-widget">
                <div class="card-body">
                    <p class="text-muted mb-1">Wallet Deductions</p>
                    <h3 class="mb-0">₦{{ number_format($metrics['wallet_deductions_today'], 2) }}</h3>
                    <span class="badge bg-info-subtle text-info mt-2">Today</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1" id="dispense">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Dispense Queue</h5>
                        <small class="text-muted">Prescriptions awaiting fulfillment</small>
                    </div>
                    <button class="btn btn-soft-primary btn-sm">Print labels</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Patient</th>
                                    <th>Drug</th>
                                    <th>Dosage</th>
                                    <th>Qty</th>
                                    <th>Cost</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pendingQueue as $prescription)
                                    <tr>
                                        <td>{{ $prescription->visit->patient->full_name }}</td>
                                        <td>{{ $prescription->drug_name }}</td>
                                        <td>{{ $prescription->dosage }} · {{ $prescription->frequency }}</td>
                                        <td>{{ $prescription->quantity }}</td>
                                        <td>₦{{ number_format($prescription->total_cost, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No pending prescriptions.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Dispense</h5>
                    <button class="btn btn-soft-secondary btn-sm">Audit log</button>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @forelse ($recentDispensed as $prescription)
                            <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <div>
                                    <p class="mb-0 fw-semibold">{{ $prescription->drug_name }}</p>
                                    <small class="text-muted">{{ $prescription->visit->patient->full_name }} · {{ $prescription->dispensed_at?->diffForHumans() }}</small>
                                </div>
                                <span class="fw-semibold text-success">₦{{ number_format($prescription->total_cost, 2) }}</span>
                            </li>
                        @empty
                            <li class="text-center text-muted py-4">No recent dispense.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-lg-6" id="visits">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Visits Awaiting Pharmacy</h5>
                    <button class="btn btn-soft-secondary btn-sm">View all</button>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse ($visitsAwaitingPharmacy as $visit)
                            <li class="list-group-item">
                                <p class="mb-1 fw-semibold">{{ $visit->patient->full_name }}</p>
                                <small class="text-muted">{{ $visit->department?->name }} · {{ $visit->scheduled_at?->format('d M h:ia') ?? 'Walk-in' }}</small>
                                <div class="mt-1">
                                    <span class="badge bg-secondary-subtle text-capitalize">{{ str_replace('_', ' ', $visit->status) }}</span>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">No visits waiting.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6" id="inventory">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Pharmacy Alerts</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning border-warning">
                        <strong>Stock Reminder:</strong> Update drug catalogue via Administration module to enable stock tracking.
                    </div>
                    <div class="alert alert-info border-info mb-0">
                        <strong>Tip:</strong> Record batch numbers during dispensing to improve traceability.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
