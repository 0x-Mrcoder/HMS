<x-doctor-layout>
    <x-slot name="header">
        Appointments
    </x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Date & Time</th>
                                    <th>Patient</th>
                                    <th>Department</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($appointments as $visit)
                                    <tr>
                                        <td>{{ $visit->scheduled_at?->format('d M Y, h:ia') }}</td>
                                        <td>
                                            <h6 class="mb-0">{{ $visit->patient->full_name }}</h6>
                                        </td>
                                        <td>{{ $visit->department?->name }}</td>
                                        <td>
                                            <span class="badge bg-secondary-subtle text-capitalize">{{ str_replace('_', ' ', $visit->status) }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('doctor.portal.visits.show', $visit) }}" class="btn btn-sm btn-soft-primary">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No appointments scheduled.</td>
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
</x-doctor-layout>
