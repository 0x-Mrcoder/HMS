@extends('layouts.staff')

@section('title', 'Edit Patient')

@section('content')
<div class="container-xxl">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Patient Details</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('staff.portal.patients.update', $patient) }}" method="POST">
                        @csrf
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="first_name" value="{{ old('first_name', $patient->first_name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="last_name" value="{{ old('last_name', $patient->last_name) }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="date_of_birth" value="{{ old('date_of_birth', $patient->date_of_birth?->format('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Gender <span class="text-danger">*</span></label>
                                <select class="form-select" name="gender" required>
                                    <option value="male" {{ $patient->gender == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ $patient->gender == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ $patient->gender == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" class="form-control" name="phone" value="{{ old('phone', $patient->phone) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" rows="2">{{ old('address', $patient->address) }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('staff.portal.patients.show', $patient) }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
