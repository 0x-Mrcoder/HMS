@extends('layouts.patient')

@section('title', 'Request a Visit')

@section('content')
<div class="container-xxl py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Request a Visit</h4>
            <small class="text-muted">Tell us why you’re visiting and when you prefer.</small>
        </div>
        <a href="{{ route('patient.portal.visits') }}" class="btn btn-outline-secondary btn-sm">Back to visits</a>
    </div>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('patient.portal.visits.store') }}" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label class="form-label">Department</label>
                    <select name="department_id" class="form-select" required>
                        <option value="">Select department</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" @selected(old('department_id') == $department->id)>{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Service</label>
                    <select name="service_id" class="form-select">
                        <option value="">Select service (optional)</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" @selected(old('service_id') == $service->id)>{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Visit Type</label>
                    <select name="visit_type" class="form-select" required>
                        <option value="opd" @selected(old('visit_type') === 'opd')>OPD</option>
                        <option value="ipd" @selected(old('visit_type') === 'ipd')>IPD</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Preferred Date/Time</label>
                    <input type="datetime-local" name="scheduled_at" class="form-control" value="{{ old('scheduled_at') }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Reason for visit</label>
                    <textarea name="reason" class="form-control" rows="3" required>{{ old('reason') }}</textarea>
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
