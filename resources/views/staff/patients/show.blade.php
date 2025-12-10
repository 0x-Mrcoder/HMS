@extends('layouts.staff')

@section('title', 'Patient Profile')

@section('content')
<div class="container-xxl">
    <div class="row">
        <!-- Sidebar Profile -->
        <div class="col-md-4 col-lg-3">
            <div class="card">
                <div class="card-body text-center">
                    <img src="{{ $patient->photo_url ?? asset('rizz-assets/images/users/avatar-1.jpg') }}" alt="" class="rounded-circle thumb-xl mb-3">
                    <h4 class="mb-0 fw-bold">{{ $patient->first_name }} {{ $patient->last_name }}</h4>
                    <p class="text-muted">{{ $patient->hospital_id }}</p>
                    
                    <div class="d-grid gap-2 mt-4">
                        <a href="{{ route('staff.portal.appointments.create', $patient) }}" class="btn btn-primary">
                            <i class="iconoir-calendar me-1"></i> Book Appointment
                        </a>
                        <a href="{{ route('staff.portal.patients.edit', $patient) }}" class="btn btn-outline-primary">
                            <i class="iconoir-edit-pencil me-1"></i> Edit Profile
                        </a>
                        <a href="{{ route('staff.portal.patients.card', $patient) }}" class="btn btn-outline-secondary">
                            <i class="iconoir-printer me-1"></i> Print Card
                        </a>
                    </div>
                </div>
            </div>

            <!-- Security Actions -->
            <div class="card border-danger">
                <div class="card-header bg-danger-subtle">
                    <h6 class="card-title text-danger mb-0">Security Actions</h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-3">Resetting the password will generate a new random password and prompt you to print a new card.</p>
                    <form action="{{ route('staff.portal.patients.reset-password', $patient) }}" method="POST" onsubmit="return confirm('Are you sure you want to reset this patient\'s password?');">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm w-100">
                            <i class="iconoir-key-alt me-1"></i> Reset Password & Print
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Details -->
        <div class="col-md-8 col-lg-9">
            <!-- Wallet Section -->
            <div class="card bg-dark text-white overflow-hidden">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <p class="text-white-50 mb-0">Wallet Balance</p>
                            <h2 class="mt-2 mb-0 fw-bold">₦ {{ number_format($patient->wallet->balance ?? 0, 2) }}</h2>
                            <div class="mt-3">
                                <span class="badge bg-white bg-opacity-10 border border-white border-opacity-25">
                                    ACC: {{ $patient->wallet->virtual_account_number ?? 'N/A' }}
                                </span>
                                <span class="badge bg-white bg-opacity-10 border border-white border-opacity-25 ms-1">
                                    {{ $patient->wallet->bank_name ?? 'CyberBank' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <i class="iconoir-wallet fs-48 text-white opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Personal Details -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Personal Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-3 fw-semibold text-muted">Full Name</div>
                        <div class="col-sm-9">{{ $patient->first_name }} {{ $patient->last_name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3 fw-semibold text-muted">Gender</div>
                        <div class="col-sm-9">{{ ucfirst($patient->gender) }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3 fw-semibold text-muted">Date of Birth</div>
                        <div class="col-sm-9">{{ $patient->date_of_birth ? $patient->date_of_birth->format('M d, Y') : 'N/A' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3 fw-semibold text-muted">Phone</div>
                        <div class="col-sm-9">{{ $patient->phone ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3 fw-semibold text-muted">Address</div>
                        <div class="col-sm-9">{{ $patient->address ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3 fw-semibold text-muted">Email</div>
                        <div class="col-sm-9">{{ $patient->email ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>

            <!-- Visit History (Brief) -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Recent Visits</h5>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Doctor</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($patient->visits->take(5) as $visit)
                                    <tr>
                                        <td>{{ $visit->created_at->format('M d, Y') }}</td>
                                        <td>Dr. {{ $visit->doctor->name ?? 'Unknown' }}</td>
                                        <td>{{ ucfirst($visit->status) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted">No visits recorded.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
