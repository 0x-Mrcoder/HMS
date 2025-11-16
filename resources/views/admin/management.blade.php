@extends('layouts.admin')

@section('title', 'Administration & Roles')

@section('content')
<div class="container-xxl">
    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Create Department</h5>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif
                    <form method="POST" action="{{ route('administration.departments.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                            @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Code</label>
                            <input type="text" name="code" class="form-control" value="{{ old('code') }}">
                            @error('code')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <input type="text" name="category" class="form-control" value="{{ old('category') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Head of Department</label>
                            <input type="text" name="head_of_department" class="form-control" value="{{ old('head_of_department') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Brand Color</label>
                            <input type="color" name="color" class="form-control form-control-color" value="{{ old('color', '#0d6efd') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Save Department</button>
                    </form>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Add Service</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('administration.services.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Department</label>
                            <select name="department_id" class="form-select">
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                            @error('department_id')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Service Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Service Code</label>
                            <input type="text" name="code" class="form-control" value="{{ old('code') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <input type="text" name="service_type" class="form-control" value="{{ old('service_type') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Base Price (₦)</label>
                            <input type="number" step="0.01" min="0" name="base_price" class="form-control" value="{{ old('base_price') }}">
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_billable" value="1" id="billable" @checked(old('is_billable'))>
                            <label class="form-check-label" for="billable">Billable Service</label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-outline-primary w-100">Add Service</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Configured Departments</h5>
                    <span class="text-muted small">{{ $departments->count() }} units</span>
                </div>
                <div class="accordion accordion-flush" id="departmentAccordion">
                    @forelse ($departments as $department)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{ $department->id }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#dept{{ $department->id }}" aria-expanded="false">
                                    <span class="badge me-2" style="background: {{ $department->color ?? '#e7e7e7' }}">&nbsp;</span>
                                    <strong>{{ $department->name }}</strong> · {{ $department->services->count() }} services
                                </button>
                            </h2>
                            <div id="dept{{ $department->id }}" class="accordion-collapse collapse" data-bs-parent="#departmentAccordion">
                                <div class="accordion-body">
                                    <p class="text-muted mb-2">{{ $department->description ?? 'No description provided.' }}</p>
                                    <p class="mb-1"><span class="text-muted">Head:</span> {{ $department->head_of_department ?? 'Unassigned' }}</p>
                                    <p class="mb-3"><span class="text-muted">Code:</span> {{ $department->code }}</p>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Code</th>
                                                    <th>Type</th>
                                                    <th>Price</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($department->services as $service)
                                                    <tr>
                                                        <td>{{ $service->name }}</td>
                                                        <td>{{ $service->code }}</td>
                                                        <td>{{ $service->service_type ?? 'General' }}</td>
                                                        <td>{{ $service->is_billable ? '₦' . number_format($service->base_price, 2) : 'N/A' }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-muted">No services configured.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted py-4">No departments configured.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
