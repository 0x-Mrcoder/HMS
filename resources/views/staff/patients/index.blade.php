@extends('layouts.staff')

@section('title', 'Patient Directory')

@section('content')
<div class="container-xxl">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">Patient Directory</h4>
                        </div>
                        <div class="col-auto">
                            <form action="{{ route('staff.portal.patients.index') }}" method="GET" class="d-flex gap-2">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Search Name, ID, Phone..." value="{{ request('search') }}">
                                    <button class="btn btn-primary" type="submit"><i class="iconoir-search"></i></button>
                                </div>
                                <a href="{{ route('staff.portal.patients.create') }}" class="btn btn-primary">
                                    <i class="iconoir-plus-circle me-1"></i> New Patient
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Hospital ID</th>
                                    <th>Patient Name</th>
                                    <th>Contact</th>
                                    <th>Gender</th>
                                    <th>Registered</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($patients as $patient)
                                    <tr>
                                        <td><span class="fw-medium">{{ $patient->hospital_id }}</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ $patient->photo_url ?? asset('rizz-assets/images/users/avatar-1.jpg') }}" alt="" class="thumb-md rounded-circle">
                                                </div>
                                                <div class="flex-grow-1 ms-2">
                                                    <h6 class="mb-0">{{ $patient->first_name }} {{ $patient->last_name }}</h6>
                                                    <small class="text-muted">{{ $patient->date_of_birth ? $patient->date_of_birth->age . ' yrs' : 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>{{ $patient->phone ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $patient->address ?? '' }}</small>
                                        </td>
                                        <td>{{ ucfirst($patient->gender) }}</td>
                                        <td>{{ $patient->created_at->format('M d, Y') }}</td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    Action
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('staff.portal.patients.show', $patient) }}">
                                                            <i class="iconoir-eye-alt me-2 text-muted"></i> View Profile
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('staff.portal.patients.edit', $patient) }}">
                                                            <i class="iconoir-edit-pencil me-2 text-muted"></i> Edit Details
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('staff.portal.patients.card', $patient) }}">
                                                            <i class="iconoir-printer me-2 text-muted"></i> Print Card
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="iconoir-user-x fs-24 d-block mb-2"></i>
                                                No patients found.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $patients->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
