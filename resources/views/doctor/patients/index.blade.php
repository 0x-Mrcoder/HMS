<x-doctor-layout>
    <x-slot name="header">
        Patient Search
    </x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">Patients Registry</h4>
                        </div>
                        <div class="col-auto">
                            <form method="GET" action="{{ route('doctor.portal.patients.index') }}" class="row g-2">
                                <div class="col-auto">
                                    <input type="text" name="q" value="{{ $search }}" class="form-control form-control-sm" placeholder="Search by name, ID, phone...">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-search"></i> Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Hospital ID</th>
                                    <th>Name</th>
                                    <th>Gender/Age</th>
                                    <th>Phone</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($patients as $patient)
                                    <tr>
                                        <td>{{ $patient->hospital_id }}</td>
                                        <td>
                                            <h6 class="mb-0">{{ $patient->first_name }} {{ $patient->last_name }}</h6>
                                        </td>
                                        <td>{{ ucfirst($patient->gender) }} / {{ $patient->date_of_birth?->age ?? 'N/A' }}</td>
                                        <td>{{ $patient->phone }}</td>
                                        <td>
                                            <a href="{{ route('doctor.portal.patients.show', $patient) }}" class="btn btn-sm btn-soft-primary">
                                                <i class="las la-folder-open me-1"></i> View File
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No patients found.</td>
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
</x-doctor-layout>
