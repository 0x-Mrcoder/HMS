@extends('layouts.admin')

@section('title', 'Nursing Station')

@section('content')
<div class="container-xxl">
    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Log Nursing Activity</h5>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif
                    <form method="POST" action="{{ route('nursing.notes.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Visit</label>
                            <select name="visit_id" class="form-select">
                                <option value="">Select active visit</option>
                                @foreach ($activeVisits as $visit)
                                    <option value="{{ $visit->id }}" @selected(old('visit_id') == $visit->id)>
                                        #{{ $visit->id }} · {{ $visit->patient->full_name }} ({{ ucfirst($visit->status) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('visit_id')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nurse Name</label>
                            <input type="text" name="nurse_name" class="form-control" value="{{ old('nurse_name') }}" placeholder="Eg. RN Mary" />
                            @error('nurse_name')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Activity Type</label>
                            <input type="text" name="note_type" class="form-control" value="{{ old('note_type') }}" placeholder="Medication, Vitals, Dressing...">
                            @error('note_type')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Details</label>
                            <textarea name="note" class="form-control" rows="4" placeholder="Document administered care">{{ old('note') }}</textarea>
                            @error('note')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Save Note</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET" class="row row-cols-lg-auto g-2 align-items-end">
                        <div class="col-12">
                            <label class="form-label">Filter by Visit</label>
                            <select name="visit_id" class="form-select">
                                <option value="">All visits</option>
                                @foreach ($activeVisits as $visit)
                                    <option value="{{ $visit->id }}" @selected((string) $visitId === (string) $visit->id)>
                                        #{{ $visit->id }} · {{ $visit->patient->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-outline-secondary" type="submit">Apply</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Nursing Notes</h5>
                    <span class="text-muted small">{{ $notes->total() }} entries</span>
                </div>
                <div class="list-group list-group-flush">
                    @forelse ($notes as $note)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">{{ $note->note_type ?? 'General' }} · {{ $note->visit->patient->full_name }}</h6>
                                    <small class="text-muted">By {{ $note->nurse_name }} &middot; {{ $note->recorded_at->format('d M, h:ia') }}</small>
                                </div>
                                <span class="badge bg-primary-subtle text-primary">Visit #{{ $note->visit_id }}</span>
                            </div>
                            <p class="mb-0 mt-2">{{ $note->note }}</p>
                        </div>
                    @empty
                        <div class="list-group-item text-center text-muted py-4">No nursing activities logged yet.</div>
                    @endforelse
                </div>
                <div class="card-footer">
                    {{ $notes->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
