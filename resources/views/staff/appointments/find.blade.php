@extends('layouts.staff')

@section('title', 'New Appointment')

@section('content')
<div class="container-xxl">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">New Appointment</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">Search for a patient to start booking an appointment.</p>
                    
                    <form action="{{ route('staff.portal.appointments.new') }}" method="GET">
                        <div class="input-group input-group-lg mb-4">
                            <input type="text" name="search" class="form-control" placeholder="Search Name, ID or Phone..." value="{{ request('search') }}" autofocus>
                            <button class="btn btn-primary" type="submit">
                                <i class="iconoir-search"></i> Find Patient
                            </button>
                        </div>
                    </form>

                    @if(request('search'))
                        <h6 class="text-uppercase text-muted mb-3">Search Results</h6>
                        @if($patients->count() > 0)
                            <div class="list-group">
                                @foreach($patients as $patient)
                                    <a href="{{ route('staff.portal.appointments.create', $patient) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0 text-dark">{{ $patient->first_name }} {{ $patient->last_name }}</h6>
                                            <small class="text-muted">{{ $patient->hospital_id }} &bull; {{ $patient->phone ?? 'No Phone' }}</small>
                                        </div>
                                        <button class="btn btn-sm btn-primary-subtle text-primary">
                                            Select <i class="iconoir-arrow-right list-group-icon ms-1"></i>
                                        </button>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4 border rounded bg-light">
                                <i class="iconoir-user-x fs-24 text-muted mb-2 d-block"></i>
                                <p class="text-muted mb-0">No patient found matching "{{ request('search') }}".</p>
                                <a href="{{ route('staff.portal.patients.create') }}" class="btn btn-sm btn-outline-primary mt-3">Register New Patient</a>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
