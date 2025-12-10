@extends('layouts.staff')

@section('title', 'All Appointments')

@section('content')
<div class="container-xxl">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">All Appointments</h4>
                        </div>
                        <div class="col-auto">
                            <!-- Filter Form -->
                            <form action="{{ route('staff.portal.appointments.index') }}" method="GET" class="d-flex gap-2">
                                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                                <select name="status" class="form-select">
                                    <option value="">All Statuses</option>
                                    <option value="pending_doctor" {{ request('status') == 'pending_doctor' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                <button class="btn btn-primary" type="submit"><i class="iconoir-filter"></i> Filter</button>
                                <a href="{{ route('staff.portal.appointments.index') }}" class="btn btn-outline-secondary">Clear</a>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Date & Time</th>
                                    <th>Patient</th>
                                    <th>Doctor</th>
                                    <th>Reason / Type</th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($appointments as $visit)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $visit->scheduled_at ? $visit->scheduled_at->format('M d, Y') : 'Date N/A' }}</div>
                                            <small class="text-muted">{{ $visit->scheduled_at ? $visit->scheduled_at->format('h:i A') : '--:--' }}</small>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ $visit->patient->photo_url ?? asset('rizz-assets/images/users/avatar-1.jpg') }}" class="rounded-circle thumb-sm" alt="">
                                                </div>
                                                <div class="ms-2">
                                                    <a href="{{ route('staff.portal.patients.show', $visit->patient) }}" class="text-dark fw-medium">
                                                        {{ $visit->patient->first_name }} {{ $visit->patient->last_name }}
                                                    </a>
                                                    <small class="d-block text-muted">{{ $visit->patient->hospital_id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Dr. {{ $visit->doctor->name }}</td>
                                        <td>
                                            <div class="fw-medium">{{ ucfirst(str_replace('_', ' ', $visit->visit_type)) }}</div>
                                            <small class="text-muted text-truncate d-block" style="max-width: 150px;">{{ $visit->reason ?? 'No reason provided' }}</small>
                                        </td>
                                        <td>
                                            @if($visit->status == 'pending_doctor')
                                                <span class="badge bg-warning-subtle text-warning">Pending</span>
                                            @elseif($visit->status == 'completed')
                                                <span class="badge bg-success-subtle text-success">Done</span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary">{{ ucfirst($visit->status) }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('staff.portal.patients.show', $visit->patient) }}" class="btn btn-sm btn-light">
                                                View Patient
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <i class="iconoir-calendar-cross fs-24 text-muted d-block mb-2"></i>
                                            <span class="text-muted">No appointments found matching your filters.</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $appointments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
