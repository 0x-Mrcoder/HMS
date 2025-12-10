@extends('layouts.staff')

@section('title', 'Dashboard')

@section('content')
<div class="container-xxl">
    <div class="row">
        <!-- Metric: Patients Registered Today -->
        <div class="col-md-6 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                        <div class="col-9">
                            <p class="text-dark mb-0 fw-semibold fs-14">Patients Registered Today</p>
                            <h3 class="mt-2 mb-0 fw-bold">{{ $registeredToday }}</h3>
                        </div>
                        <div class="col-3 align-self-center">
                            <div class="d-flex justify-content-center align-items-center thumb-md bg-light-alt rounded-circle mx-auto">
                                <i class="iconoir-add-user fs-24 align-self-center text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Action: Register New Patient -->
        <div class="col-md-6 col-lg-4">
            <div class="card bg-primary">
                <div class="card-body">
                    <div class="row d-flex justify-content-center pb-3">
                        <div class="col-9">
                            <p class="text-white-50 mb-0 fw-semibold fs-14">Quick Action</p>
                            <h3 class="mt-2 mb-0 fw-bold text-white">New Patient</h3>
                            <a href="{{ route('staff.portal.patients.create') }}" class="btn btn-sm btn-light mt-3">Register Now</a>
                        </div>
                        <div class="col-3 align-self-center">
                             <div class="d-flex justify-content-center align-items-center thumb-md bg-white-50 rounded-circle mx-auto">
                                <i class="iconoir-plus fs-24 align-self-center text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Patients -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Recently Registered Patients</h4>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Patient Name</th>
                                    <th>Hospital ID</th>
                                    <th>Gender</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPatients as $patient)
                                    <tr>
                                        <td>{{ $patient->created_at->format('M d, H:i') }}</td>
                                        <td class="fw-medium">{{ $patient->name }}</td>
                                        <td>{{ $patient->hospital_id }}</td>
                                        <td>{{ ucfirst($patient->gender) }}</td>
                                        <td><span class="badge bg-success-subtle text-success">Active</span></td>
                                        <td>
                                            <!-- Print Card Feature -->
                                            <!-- Note: Password is only available immediately after creation, 
                                                 so re-printing might need a "Reset Password" flow or just printing card without password.
                                                 For now, we link to a generic card print (maybe without password if not in session). -->
                                            <a href="{{ route('staff.portal.patients.card', $patient) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="iconoir-printer"></i> Card
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center py-4 text-muted">No patients registered yet.</td></tr>
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
