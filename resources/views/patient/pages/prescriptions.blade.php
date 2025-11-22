@extends('layouts.patient')

@section('title', 'Prescriptions')

@section('content')
<div class="container-xxl py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Prescriptions</h4>
            <small class="text-muted">Medications from your clinic visits.</small>
        </div>
        <a href="{{ route('patient.portal.dashboard') }}" class="btn btn-outline-secondary btn-sm">Back to dashboard</a>
    </div>
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Drug</th>
                            <th>Dosage</th>
                            <th>Frequency</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Clinic</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prescriptions as $prescription)
                            <tr>
                                <td class="fw-semibold">{{ $prescription->drug_name }}</td>
                                <td>{{ $prescription->dosage }}</td>
                                <td>{{ $prescription->frequency }}</td>
                                <td>{{ $prescription->duration }}</td>
                                <td><span class="badge bg-secondary-subtle text-capitalize">{{ $prescription->status }}</span></td>
                                <td>{{ $prescription->visit?->department?->name ?? 'N/A' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('patient.portal.prescriptions.show', $prescription) }}" class="btn btn-soft-secondary btn-sm">Details</a>
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
            <div class="p-3">
                {{ $prescriptions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
