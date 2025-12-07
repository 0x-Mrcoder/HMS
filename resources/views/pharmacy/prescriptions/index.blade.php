<x-pharmacy-layout>
    <x-slot name="header">All Prescriptions</x-slot>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Patient</th>
                            <th>Drug</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prescriptions as $rx)
                            <tr>
                                <td>{{ $rx->created_at->format('M d, H:i') }}</td>
                                <td>
                                    <h6 class="mb-0">{{ $rx->visit->patient->first_name }} {{ $rx->visit->patient->last_name }}</h6>
                                </td>
                                <td>{{ $rx->drug_name }} ({{ $rx->quantity }})</td>
                                <td>
                                    <span class="badge {{ $rx->status === 'dispensed' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }}">
                                        {{ ucfirst($rx->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('pharmacy.portal.prescriptions.show', $rx) }}" class="btn btn-sm btn-primary">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4 text-muted">No prescriptions found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $prescriptions->links() }}
            </div>
        </div>
    </div>
</x-pharmacy-layout>
