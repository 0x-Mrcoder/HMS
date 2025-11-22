@extends('layouts.patient')

@section('title', 'Visits & Appointments')

@section('content')
<div class="container-xxl py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Visits &amp; Appointments</h4>
            <small class="text-muted">Your scheduled and past hospital visits.</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('patient.portal.visits.request') }}" class="btn btn-primary btn-sm"><i class="iconoir-calendar me-1"></i>Request Visit</a>
            <a href="{{ route('patient.portal.dashboard') }}" class="btn btn-outline-secondary btn-sm">Back to dashboard</a>
        </div>
    </div>
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Department</th>
                            <th>Service</th>
                            <th>Status</th>
                            <th>Doctor</th>
                            <th>Scheduled</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($visits as $visit)
                            <tr>
                                <td>{{ $visit->department?->name ?? 'General' }}</td>
                                <td>{{ $visit->service?->name ?? 'Custom service' }}</td>
                                <td><span class="badge bg-secondary-subtle text-capitalize">{{ str_replace('_', ' ', $visit->status) }}</span></td>
                                <td>{{ $visit->doctor_name ?? '--' }}</td>
                                <td>{{ $visit->scheduled_at?->format('d M Y, h:ia') ?? 'Pending' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No visits recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $visits->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
