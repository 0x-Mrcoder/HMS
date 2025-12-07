<x-doctor-layout>
    <x-slot name="header">
        Prescription History
    </x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Patient</th>
                                    <th>Drug & Dosage</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($prescriptions as $prescription)
                                    <tr>
                                        <td>{{ $prescription->created_at->format('d M Y') }}</td>
                                        <td>
                                            <h6 class="mb-0">{{ $prescription->visit->patient->full_name }}</h6>
                                        </td>
                                        <td>
                                            <p class="mb-0 fw-medium">{{ $prescription->drug_name }}</p>
                                            <small class="text-muted">{{ $prescription->dosage }} {{ $prescription->frequency }}</small>
                                        </td>
                                        <td>
                                            <span class="badge {{ $prescription->status === 'dispensed' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }}">
                                                {{ ucfirst($prescription->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">No prescriptions found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $prescriptions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-doctor-layout>
