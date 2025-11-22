@extends('layouts.admin')

@section('title', 'Patients')

@section('content')
<div class="container-xxl">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mb-0">Patient Registry</h4>
                        <small class="text-muted">Track everyone registered into {{ $hospitalConfig['name'] ?? 'the hospital' }}</small>
                    </div>
                    <a href="{{ route('patients.create') }}" class="btn btn-primary"><i class="iconoir-add-circle me-1"></i>New Patient</a>
                </div>
                <div class="card-body border-dashed border-top">
                    <form method="GET" class="row g-2 align-items-center">
                        <div class="col-lg-4 col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0"><i class="iconoir-search"></i></span>
                                <input type="search" class="form-control border-start-0" placeholder="Search by name, ID or phone" name="q" value="{{ $search }}">
                            </div>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-outline-secondary" type="submit">Filter</button>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Patient</th>
                                <th>Hospital ID</th>
                                <th>Phone</th>
                                <th>Location</th>
                                <th>Wallet</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($patients as $patient)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-2" style="width:38px;height:38px;">
                                                {{ strtoupper(substr($patient->first_name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="mb-0 fw-semibold">{{ $patient->full_name }}</p>
                                                <small class="text-muted">{{ ucfirst($patient->gender) }} · {{ $patient->date_of_birth?->format('d M Y') ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-info-subtle text-info">{{ $patient->hospital_id }}</span></td>
                                    <td>{{ $patient->phone }}</td>
                                    <td>{{ $patient->city }}, {{ $patient->state }}</td>
                                    <td>
                                        <span class="fw-semibold">₦{{ number_format(optional($patient->wallet)->balance ?? 0, 2) }}</span>
                                        @if(optional($patient->wallet)->low_balance)
                                            <span class="badge bg-danger-subtle text-danger ms-1">Low</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('patients.show', $patient) }}" class="btn btn-soft-primary btn-sm">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No patients found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $patients->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
