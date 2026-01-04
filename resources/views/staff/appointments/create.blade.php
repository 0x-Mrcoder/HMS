@extends('layouts.staff')

@section('title', 'Book Appointment')

@section('content')
<div class="container-xxl">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0 text-center">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <div class="bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="bi bi-calendar-plus fs-3 text-primary"></i>
                        </div>
                        <div class="text-start">
                            <h4 class="card-title fw-bold mb-1">Book Appointment</h4>
                            <p class="text-muted small mb-0">Schedule a visit for <span class="fw-medium text-dark">{{ $patient->first_name }} {{ $patient->last_name }}</span></p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('staff.portal.appointments.store', $patient) }}" method="POST">
                        @csrf
                        
                        <!-- Hidden Patient Context -->
                        <div class="alert alert-light border border-light-subtle d-flex align-items-center mb-4" role="alert">
                            <i class="bi bi-person-check fs-4 text-primary me-3"></i>
                            <div>
                                <h6 class="mb-0 text-dark">Patient: {{ $patient->first_name }} {{ $patient->last_name }}</h6>
                                <small class="text-muted">ID: {{ $patient->hospital_id }}</small>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                             <div class="col-md-4">
                                <label class="form-label fw-medium">Select Doctor <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-people"></i></span>
                                    <select class="form-select border-start-0 ps-0" name="doctor_id" required>
                                        <option value="" selected disabled>Choose a doctor...</option>
                                        @php $currentDept = ''; @endphp
                                        @foreach($doctors as $doc)
                                            @if($currentDept != $doc->department->name)
                                                @if($currentDept != '') </optgroup> @endif
                                                <optgroup label="{{ $doc->department->name }}">
                                                @php $currentDept = $doc->department->name; @endphp
                                            @endif
                                            <option value="{{ $doc->user_id }}">{{ $doc->user->name }} ({{ $doc->specialization }})</option>
                                        @endforeach
                                        @if($currentDept != '') </optgroup> @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Visit Type <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-clipboard-pulse"></i></span>
                                    <select class="form-select border-start-0 ps-0" name="visit_type" required>
                                        <option value="consultation">General Consultation</option>
                                        <option value="checkup">Routine Checkup</option>
                                        <option value="follow_up">Follow Up</option>
                                        <option value="emergency">Emergency</option>
                                    </select>
                                </div>
                            </div>
                             <div class="col-md-4">
                                <label class="form-label fw-medium">Schedule Date & Time</label>
                                <input type="datetime-local" class="form-control" name="scheduled_at" value="{{ now()->format('Y-m-d\TH:i') }}">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium">Reason for Visit</label>
                            <textarea class="form-control" name="reason" rows="3" placeholder="Briefly describe symptoms or purpose..."></textarea>
                        </div>

                        <!-- Vitals Section -->
                        <h6 class="fw-bold text-success text-uppercase letter-spacing-1 mb-3 pt-3 border-top">
                            <i class="bi bi-activity me-2"></i>Triage / Vitals (Optional)
                        </h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Temperature (°C)</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" step="0.1" class="form-control" name="vitals[temperature]" placeholder="36.5">
                                    <span class="input-group-text">°C</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Blood Pressure</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" name="vitals[blood_pressure]" placeholder="120/80">
                                    <span class="input-group-text">mmHg</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Pulse Rate</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" class="form-control" name="vitals[pulse_rate]" placeholder="72">
                                    <span class="input-group-text">bpm</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Weight (kg)</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" step="0.1" class="form-control" name="vitals[weight]" placeholder="70.5">
                                    <span class="input-group-text">kg</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('staff.portal.patients.show', $patient) }}" class="btn btn-light px-4">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-calendar-check me-2"></i>Content Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
