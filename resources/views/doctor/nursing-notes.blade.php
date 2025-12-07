<x-doctor-layout>
    <x-slot name="header">
        Nursing Notes
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
                                    <th>Nurse</th>
                                    <th>Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($notes as $note)
                                    <tr>
                                        <td>{{ $note->recorded_at->format('d M Y, H:i') }}</td>
                                        <td>
                                            <h6 class="mb-0">{{ $note->visit->patient->full_name }}</h6>
                                        </td>
                                        <td>{{ $note->nurse_name }}</td>
                                        <td>{{ Str::limit($note->note, 100) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">No nursing notes found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $notes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-doctor-layout>
