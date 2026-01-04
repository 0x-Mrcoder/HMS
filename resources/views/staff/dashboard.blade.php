@extends('layouts.staff')

@section('title', 'Dashboard')

@section('content')
<style>
    .card-animate {
        transition: all 0.3s ease;
    }
    .card-animate:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
</style>
<div class="container-xxl pt-1 mt-1">
    
    <!-- Welcome Header -->
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h4 class="mb-0">Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 17 ? 'Afternoon' : 'Evening') }}, {{ Auth::user()->first_name ?? 'Staff' }}</h4>
            <p class="text-muted mb-0">Today is {{ now()->format('l, F jS') }}. Here's what's happening at the front desk.</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('staff.portal.patients.create') }}" class="btn btn-primary btn-sm me-2">
                <i class="bi bi-person-plus"></i> Register Patient
            </a>
            <a href="{{ route('staff.portal.appointments.new') }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-calendar-plus"></i> New Booking
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="row">
        <!-- Total Patients -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100 card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted fw-medium mb-1 fs-13 text-uppercase">Total Patients</p>
                            <h3 class="my-0 fw-bold text-dark">{{ $totalPatients }}</h3>
                        </div>
                        <div class="thumb-md bg-primary-subtle rounded d-flex align-items-center justify-content-center">
                            <i class="bi bi-people fs-3 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Patients Today -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100 card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted fw-medium mb-1 fs-13 text-uppercase">Registered Today</p>
                            <h3 class="my-0 fw-bold text-dark">{{ $registeredToday }}</h3>
                        </div>
                        <div class="thumb-md bg-success-subtle rounded d-flex align-items-center justify-content-center">
                            <i class="bi bi-person-add fs-3 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Appointments Today -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100 card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted fw-medium mb-1 fs-13 text-uppercase">Appointments Today</p>
                            <h3 class="my-0 fw-bold text-dark">{{ $todayAppointments->count() }}</h3>
                        </div>
                        <div class="thumb-md bg-warning-subtle rounded d-flex align-items-center justify-content-center">
                            <i class="bi bi-calendar-check fs-3 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Actions -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted fw-medium mb-1 fs-13 text-uppercase">Pending Confirm</p>
                            <h3 class="my-0 fw-bold text-dark">{{ $pendingAppointments }}</h3>
                        </div>
                        <div class="thumb-md bg-info-subtle rounded d-flex align-items-center justify-content-center">
                            <i class="bi bi-bell fs-3 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Operations Area -->
    <div class="row mt-4">
        <!-- Today's Schedule -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-bottom-0 pb-0 pt-3">
                    <div>
                        <h5 class="card-title mb-0">Today's Schedule</h5>
                        <p class="text-muted small mb-0">Overview of patient visits scheduled for {{ now()->format('M d') }}</p>
                    </div>
                    <a href="{{ route('staff.portal.appointments.index') }}" class="text-primary fw-medium fs-13">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-0">Time</th>
                                    <th>Patient</th>
                                    <th>Doctor</th>
                                    <th>Reason</th>
                                    <th class="text-end">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($todayAppointments as $visit)
                                    <tr>
                                        <td class="ps-0 py-3">
                                            <span class="fw-bold fs-14 text-dark">{{ $visit->scheduled_at->format('h:i') }}</span>
                                            <span class="text-muted fs-11 d-block">{{ $visit->scheduled_at->format('A') }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="thumb-sm rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold me-2">
                                                    {{ substr($visit->patient->first_name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <a href="{{ route('staff.portal.patients.show', $visit->patient) }}" class="text-dark fw-semibold d-block">
                                                        {{ $visit->patient->first_name }} {{ $visit->patient->last_name }}
                                                    </a>
                                                    <small class="text-muted">{{ $visit->patient->hospital_id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border border-light-subtle">Dr. {{ $visit->doctor->name }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fs-13 fw-medium">{{ ucfirst(str_replace('_', ' ', $visit->visit_type)) }}</span>
                                                <small class="text-muted text-truncate" style="max-width: 120px;">{{ $visit->reason ?? 'N/A' }}</small>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            @if($visit->status == 'pending_doctor')
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1">Pending</span>
                                            @elseif($visit->status == 'completed')
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Done</span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary px-2 py-1">{{ ucfirst($visit->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="avatar-md bg-light bg-opacity-50 rounded-circle mx-auto mb-3 d-flex justify-content-center align-items-center">
                                                <i class="iconoir-calendar text-muted fs-24"></i>
                                            </div>
                                            <h6 class="text-muted mb-1">No appointments today</h6>
                                            <p class="text-muted small mb-3">Enjoy your free time!</p>
                                            <a href="{{ route('staff.portal.appointments.new') }}" class="btn btn-sm btn-outline-primary">Book Now</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Registrations (Sidebar) -->
        <div class="col-lg-4">
            <!-- Quick Actions Card (Optional Enhancement) -->
            <!-- <div class="card mb-3 bg-dark text-white ..."> ... </div> -->

            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-transparent border-bottom-0 pt-3">
                    <h5 class="card-title mb-0">Recent Registrations</h5>
                </div>
                <div class="card-body pt-0">
                    <div class="list-group list-group-flush">
                        @forelse($recentPatients as $patient)
                            <div class="list-group-item px-0 border-0 mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="thumb-md rounded bg-light d-flex align-items-center justify-content-center me-3">
                                        <i class="bi bi-person fs-5 text-muted"></i>
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h6 class="mb-1 text-truncate fw-medium">{{ $patient->name }}</h6>
                                        <div class="d-flex align-items-center text-muted fs-12">
                                            <span class="me-2">{{ $patient->gender == 'male' ? 'M' : 'F' }}</span>
                                            <span class="me-2">&bull;</span>
                                            <span>{{ $patient->created_at->diffForHumans(null, true) }} ago</span>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0 ms-2">
                                        <a href="{{ route('staff.portal.patients.card', $patient) }}" class="btn btn-icon btn-sm btn-ghost-primary" title="Print Card">
                                            <i class="bi bi-printer fs-5"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">No recent patients.</div>
                        @endforelse
                    </div>
                    <div class="mt-3 text-center">
                        <a href="{{ route('staff.portal.patients.index') }}" class="btn btn-sm btn-link text-primary text-decoration-none">View Patient Directory <i class="bi bi-arrow-right fs-12 ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
