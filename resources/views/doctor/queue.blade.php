<x-doctor-layout>
    <x-slot name="header">
        Clinic Queue
    </x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Patient</th>
                                    <th>Department</th>
                                    <th>Scheduled Time</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($queue as $visit)
                                    <tr>
                                        <td>
                                            <h6 class="mb-0">{{ $visit->patient->full_name }}</h6>
                                            <small class="text-muted">{{ $visit->patient->hospital_id }}</small>
                                        </td>
                                        <td>{{ $visit->department?->name }}</td>
                                        <td>{{ $visit->scheduled_at?->format('h:ia') ?? 'Walk-in' }}</td>
                                        <td>
                                            @php
                                                $badgeClass = match($visit->status) {
                                                    'completed', 'paid' => 'bg-success-subtle text-success',
                                                    'in_progress', 'admitted' => 'bg-info-subtle text-info',
                                                    'pending_doctor' => 'bg-warning-subtle text-warning',
                                                    'cancelled' => 'bg-danger-subtle text-danger',
                                                    default => 'bg-secondary-subtle text-secondary'
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }} text-capitalize">{{ str_replace('_', ' ', $visit->status) }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('doctor.portal.visits.show', $visit) }}" class="btn btn-sm btn-primary">
                                                Consult
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No patients in queue.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $queue->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-doctor-layout>
