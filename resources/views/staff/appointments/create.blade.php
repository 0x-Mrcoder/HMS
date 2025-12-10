@extends('layouts.staff')

@section('title', 'Book Appointment')

@section('content')
<div class="container-xxl">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Book Function with Doctor</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('staff.portal.appointments.store', $patient) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Patient</label>
                            <input type="text" class="form-control" value="{{ $patient->first_name }} {{ $patient->last_name }} ({{ $patient->hospital_id }})" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Select Doctor <span class="text-danger">*</span></label>
                            <select class="form-select" name="doctor_id" required>
                                <option value="" selected disabled>Choose a doctor...</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">Dr. {{ $doctor->name }} ({{ $doctor->department->name ?? 'General' }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Visit Type <span class="text-danger">*</span></label>
                            <select class="form-select" name="visit_type" required>
                                <option value="consultation">General Consultation</option>
                                <option value="checkup">Routine Checkup</option>
                                <option value="follow_up">Follow Up</option>
                                <option value="emergency">Emergency</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Schedule Date & Time</label>
                            <input type="datetime-local" class="form-control" name="scheduled_at" value="{{ now()->format('Y-m-d\TH:i') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Reason for Visit</label>
                            <textarea class="form-control" name="reason" rows="3" placeholder="Briefly describe symptoms or purpose..."></textarea>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('staff.portal.patients.show', $patient) }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="iconoir-calendar me-1"></i> Book Appointment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
