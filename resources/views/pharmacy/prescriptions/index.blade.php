<x-pharmacy-layout>
    <x-slot name="header">Prescriptions Management</x-slot>

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <ul class="nav nav-pills bg-light rounded p-1" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') == 'pending' || !request('status') ? 'active' : '' }}" href="{{ route('pharmacy.portal.prescriptions.index', ['status' => 'pending']) }}">
                            Pending
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') == 'dispensed' ? 'active' : '' }}" href="{{ route('pharmacy.portal.prescriptions.index', ['status' => 'dispensed']) }}">
                            Dispensed
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') == 'all' ? 'active' : '' }}" href="{{ route('pharmacy.portal.prescriptions.index', ['status' => 'all']) }}">
                            All History
                        </a>
                    </li>
                </ul>

                <form action="{{ route('pharmacy.portal.prescriptions.index') }}" method="GET" class="d-flex" style="max-width: 300px;">
                    <input type="hidden" name="status" value="{{ request('status', 'all') }}">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search Patient Name/ID..." value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit"><i class="iconoir-search"></i></button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table mb-0 align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Drug Details</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prescriptions as $rx)
                            <tr>
                                <td>{{ $rx->created_at->format('M d, H:i') }}</td>
                                <td>
                                    <h6 class="mb-0">{{ $rx->visit->patient->first_name }} {{ $rx->visit->patient->last_name }}</h6>
                                    <small class="text-muted">{{ $rx->visit->patient->hospital_id }}</small>
                                </td>
                                <td>Dr. {{ $rx->visit->doctor->name ?? 'Unknown' }}</td>
                                <td>
                                    <span class="fw-medium">{{ $rx->drug_name }}</span>
                                    <span class="text-muted">x {{ $rx->quantity }}</span>
                                </td>
                                <td>
                                    @if($rx->status == 'pending')
                                        <span class="badge bg-warning-subtle text-warning">Pending</span>
                                    @elseif($rx->status == 'dispensed')
                                        <span class="badge bg-success-subtle text-success">Dispensed</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger">{{ ucfirst($rx->status) }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('pharmacy.portal.prescriptions.show', $rx) }}" class="btn btn-sm {{ $rx->status == 'pending' ? 'btn-primary' : 'btn-soft-primary' }}">
                                        {{ $rx->status == 'pending' ? 'Dispense' : 'View' }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-5 text-muted">No prescriptions found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $prescriptions->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-pharmacy-layout>
