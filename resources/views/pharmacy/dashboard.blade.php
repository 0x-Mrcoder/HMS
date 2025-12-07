<x-pharmacy-layout>
    <x-slot name="header">
        Dashboard
    </x-slot>

    <div class="row">
        <!-- Metrics -->
        <div class="col-md-6 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                        <div class="col-9">
                            <p class="text-dark mb-0 fw-semibold fs-14">Pending Orders</p>
                            <h3 class="mt-2 mb-0 fw-bold">{{ $metrics['pending_orders'] }}</h3>
                        </div>
                        <div class="col-3 align-self-center">
                            <div class="d-flex justify-content-center align-items-center thumb-md bg-light-alt rounded-circle mx-auto">
                                <i class="iconoir-rx fs-24 align-self-center text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                        <div class="col-9">
                            <p class="text-dark mb-0 fw-semibold fs-14">Dispensed Today</p>
                            <h3 class="mt-2 mb-0 fw-bold">{{ $metrics['dispensed_today'] }}</h3>
                        </div>
                        <div class="col-3 align-self-center">
                            <div class="d-flex justify-content-center align-items-center thumb-md bg-light-alt rounded-circle mx-auto">
                                <i class="iconoir-check-circle fs-24 align-self-center text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                        <div class="col-9">
                            <p class="text-dark mb-0 fw-semibold fs-14">Today's Sales</p>
                            <h3 class="mt-2 mb-0 fw-bold">₦{{ number_format($metrics['wallet_deductions_today'], 2) }}</h3>
                        </div>
                        <div class="col-3 align-self-center">
                            <div class="d-flex justify-content-center align-items-center thumb-md bg-light-alt rounded-circle mx-auto">
                                <i class="iconoir-wallet fs-24 align-self-center text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <!-- Pending Prescriptions List -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">Pending Prescriptions</h4>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('pharmacy.portal.prescriptions.index') }}" class="text-primary">View All</a>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Patient</th>
                                    <th>Drug</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingQueue as $rx)
                                    <tr>
                                        <td>{{ $rx->created_at->format('M d, H:i') }}</td>
                                        <td>
                                            <h6 class="mb-0">{{ $rx->visit->patient->first_name }} {{ $rx->visit->patient->last_name }}</h6>
                                            <small class="text-muted">#{{ $rx->visit->patient->hospital_id }}</small>
                                        </td>
                                        <td>{{ $rx->drug_name }} ({{ $rx->quantity }})</td>
                                        <td><span class="badge bg-warning-subtle text-warning">Pending</span></td>
                                        <td>
                                            <a href="{{ route('pharmacy.portal.prescriptions.show', $rx) }}" class="btn btn-sm btn-primary">
                                                Dispense
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">No pending prescriptions.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-pharmacy-layout>
