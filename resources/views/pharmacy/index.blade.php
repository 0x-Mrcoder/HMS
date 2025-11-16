@extends('layouts.admin')

@section('title', 'Pharmacy Queue')

@section('content')
<div class="container-xxl">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-3 gap-3">
        <div>
            <h3 class="mb-0">Pharmacy Fulfilment</h3>
            <small class="text-muted">Monitor prescriptions awaiting dispensing</small>
        </div>
        <form method="GET" class="row row-cols-lg-auto g-2">
            <div class="col-12">
                <input type="search" class="form-control" name="q" value="{{ $search }}" placeholder="Search drug, patient or ID">
            </div>
            <div class="col-12">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    @foreach(['pending' => 'Pending', 'dispensed' => 'Dispensed', 'rejected' => 'Rejected'] as $key => $label)
                        <option value="{{ $key }}" @selected($status === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <button class="btn btn-primary" type="submit">Filter</button>
            </div>
        </form>
    </div>

    <div class="row g-3 mb-3">
        @foreach(['pending' => 'info', 'dispensed' => 'success', 'rejected' => 'danger'] as $state => $color)
            <div class="col-md-4">
                <div class="card border-{{ $color }}-subtle">
                    <div class="card-body">
                        <p class="text-muted text-uppercase mb-1">{{ ucfirst($state) }}</p>
                        <h3 class="mb-0">{{ number_format($statusCounts[$state] ?? 0) }}</h3>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Prescription Queue</h5>
            <span class="text-muted small">{{ $prescriptions->total() }} records</span>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Patient</th>
                        <th>Medication</th>
                        <th>Qty</th>
                        <th>Cost</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($prescriptions as $prescription)
                        <tr>
                            <td>
                                <p class="mb-0 fw-semibold">{{ $prescription->visit->patient->full_name }}</p>
                                <small class="text-muted">{{ $prescription->visit->patient->hospital_id }}</small>
                            </td>
                            <td>
                                <p class="mb-0">{{ $prescription->drug_name }}</p>
                                <small class="text-muted">{{ $prescription->dosage }} · {{ $prescription->frequency }}</small>
                            </td>
                            <td>{{ $prescription->quantity }}</td>
                            <td>₦{{ number_format($prescription->total_cost, 2) }}</td>
                            <td><span class="badge bg-secondary-subtle text-capitalize">{{ $prescription->status }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('pharmacy.prescriptions.show', $prescription) }}" class="btn btn-soft-primary btn-sm">Details</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No prescriptions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $prescriptions->links() }}
        </div>
    </div>
</div>
@endsection
