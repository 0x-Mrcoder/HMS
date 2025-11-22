@extends('layouts.patient')

@section('title', 'Visit Details')

@section('content')
<div class="container-xxl py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Visit Details</h4>
            <small class="text-muted">Department: {{ $visit->department?->name ?? 'General' }} · Service: {{ $visit->service?->name ?? 'Custom' }}</small>
        </div>
        <a href="{{ route('patient.portal.visits') }}" class="btn btn-outline-secondary btn-sm">Back to visits</a>
    </div>
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <p class="mb-1 text-muted">Status</p>
                    <span class="badge bg-secondary-subtle text-capitalize">{{ str_replace('_', ' ', $visit->status) }}</span>
                </div>
                <form method="POST" action="{{ route('patient.portal.visits.cancel', $visit) }}" onsubmit="return confirm('Cancel this visit request?');">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm" type="submit">Cancel</button>
                </form>
            </div>
            <form method="POST" action="{{ route('patient.portal.visits.update', $visit) }}" class="row g-3">
                @csrf
                @method('PATCH')
                <div class="col-md-6">
                    <label class="form-label">Preferred Date/Time</label>
                    <input type="datetime-local" name="scheduled_at" class="form-control" value="{{ old('scheduled_at', $visit->scheduled_at?->format('Y-m-d\TH:i')) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Reason for visit</label>
                    <textarea name="reason" class="form-control" rows="3" required>{{ old('reason', $visit->reason) }}</textarea>
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <button class="btn btn-primary" type="submit">Update Visit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
