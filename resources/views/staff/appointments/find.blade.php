@extends('layouts.staff')

@section('title', 'New Appointment')

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
                            <h4 class="card-title fw-bold mb-1">New Appointment</h4>
                            <p class="text-muted small mb-0">Search for a patient to start booking.</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('staff.portal.appointments.new') }}" method="GET">
                        <div class="input-group input-group-lg mb-4">
                            <span class="input-group-text bg-light border-end-0 ps-3"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Search by Name, ID or Phone..." value="{{ request('search') }}" autofocus>
                            <button class="btn btn-primary" type="submit">
                                Find Patient
                            </button>
                        </div>
                    </form>

                    @if(request('search'))
                        <h6 class="text-uppercase text-muted mb-3 fs-13 fw-semibold">Search Results</h6>
                        @if($patients->count() > 0)
                            <div class="list-group">
                                @foreach($patients as $patient)
                                    <a href="{{ route('staff.portal.appointments.create', $patient) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3 border-light-subtle mb-2 rounded border">
                                        <div class="d-flex align-items-center">
                                            <div class="thumb-md rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3 fw-bold fs-5">
                                                {{ substr($patient->first_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <h6 class="mb-1 text-dark fw-bold">{{ $patient->first_name }} {{ $patient->last_name }}</h6>
                                                <small class="text-muted"><i class="bi bi-postcard me-1"></i>{{ $patient->hospital_id }} &bull; <i class="bi bi-telephone ms-2 me-1"></i>{{ $patient->phone ?? 'No Phone' }}</small>
                                            </div>
                                        </div>
                                        <div class="btn btn-sm btn-light text-primary fw-medium rounded-pill px-3">
                                            Select <i class="bi bi-arrow-right ms-1"></i>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5 border border-dashed rounded bg-light-subtle">
                                <i class="bi bi-person-x fs-1 text-muted mb-3 d-block opacity-50"></i>
                                <h6 class="text-dark fw-medium mb-1">No patient found</h6>
                                <p class="text-muted small mb-3">We couldn't find any patient matching "{{ request('search') }}".</p>
                                <a href="{{ route('staff.portal.patients.create') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-person-plus me-1"></i> Register New Patient
                                </a>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
