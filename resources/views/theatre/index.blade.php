@extends('layouts.admin')

@section('title', 'Theatre Schedule')

@section('content')
<div class="container-xxl">
    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Schedule Procedure</h5>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif
                    <form method="POST" action="{{ route('theatre.surgeries.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Patient</label>
                            <select name="patient_id" class="form-select">
                                <option value="">Select patient</option>
                                @foreach ($openVisits as $visit)
                                    <option value="{{ $visit->patient->id }}" @selected(old('patient_id') == $visit->patient->id)>
                                        {{ $visit->patient->full_name }} · {{ $visit->patient->hospital_id }}
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Linked Visit</label>
                            <select name="visit_id" class="form-select">
                                <option value="">Optional</option>
                                @foreach ($openVisits as $visit)
                                    <option value="{{ $visit->id }}" @selected(old('visit_id') == $visit->id)>
                                        Visit #{{ $visit->id }} · {{ $visit->patient->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('visit_id')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Procedure Name</label>
                            <input type="text" name="procedure_name" class="form-control" value="{{ old('procedure_name') }}" placeholder="Eg. Appendectomy">
                            @error('procedure_name')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Surgeon</label>
                            <input type="text" name="surgeon_name" class="form-control" value="{{ old('surgeon_name') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Scheduled At</label>
                            <input type="datetime-local" name="scheduled_at" class="form-control" value="{{ old('scheduled_at') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Estimated Cost (₦)</label>
                            <input type="number" step="0.01" min="0" name="estimated_cost" class="form-control" value="{{ old('estimated_cost') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Materials (one per line)</label>
                            <textarea name="materials_used" class="form-control" rows="3" placeholder="Gauze\nSuture">{{ old('materials_used') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Create Surgery</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET" class="row row-cols-lg-auto g-2 align-items-end">
                        <div class="col-12">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All</option>
                                @foreach(['scheduled' => 'Scheduled', 'in_progress' => 'In Progress', 'completed' => 'Completed', 'billed' => 'Billed'] as $key => $label)
                                    <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Search</label>
                            <input type="search" class="form-control" name="q" value="{{ request('q') }}" placeholder="Procedure or patient">
                        </div>
                        <div class="col-12">
                            <button class="btn btn-outline-secondary" type="submit">Apply</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Scheduled Procedures</h5>
                    <span class="text-muted small">{{ $surgeries->total() }} records</span>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Patient</th>
                                <th>Procedure</th>
                                <th>Status</th>
                                <th>Cost</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($surgeries as $surgery)
                                <tr>
                                    <td>
                                        <p class="mb-0 fw-semibold">{{ $surgery->patient->full_name }}</p>
                                        <small class="text-muted">{{ $surgery->patient->hospital_id }}</small>
                                    </td>
                                    <td>
                                        <p class="mb-0">{{ $surgery->procedure_name }}</p>
                                        <small class="text-muted">{{ $surgery->surgeon_name ?? 'Awaiting surgeon' }}</small>
                                    </td>
                                    <td><span class="badge bg-secondary-subtle text-capitalize">{{ str_replace('_', ' ', $surgery->status) }}</span></td>
                                    <td>₦{{ number_format($surgery->actual_cost ?: $surgery->estimated_cost, 2) }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('theatre.surgeries.show', $surgery) }}" class="btn btn-soft-primary btn-sm">Details</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No surgeries scheduled.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $surgeries->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
