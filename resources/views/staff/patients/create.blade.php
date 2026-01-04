@extends('layouts.staff')

@section('title', 'Register Patient')

@section('content')
<div class="container-xxl">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0 text-center">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <div class="bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="bi bi-person-plus fs-3 text-primary"></i>
                        </div>
                        <div class="text-start">
                            <h4 class="card-title fw-bold mb-1">Register New Patient</h4>
                            <p class="text-muted small mb-0">Enter details to create a record.</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('staff.portal.patients.store') }}" method="POST">
                        @csrf
                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-medium">First Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control border-start-0" name="first_name" placeholder="John" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Last Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control border-start-0" name="last_name" placeholder="Doe" required>
                                </div>
                            </div>
                             <div class="col-md-4">
                                <label class="form-label fw-medium">Date of Birth <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="date_of_birth" required>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                           <div class="col-md-4">
                                <label class="form-label fw-medium">Gender <span class="text-danger">*</span></label>
                                <select class="form-select" name="gender" required>
                                    <option value="" selected disabled>Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-telephone"></i></span>
                                    <input type="text" class="form-control border-start-0" name="phone" placeholder="+1 234 567 8900">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Email Address <span class="text-muted fw-normal">(Optional)</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control border-start-0" name="email" placeholder="email@example.com">
                                </div>
                                <div class="form-text text-muted ps-1 fs-11 mt-1">System generates email if empty.</div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium">Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-geo-alt"></i></span>
                                <input type="text" class="form-control border-start-0" name="address" placeholder="123 Main St, City, Country">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('staff.portal.dashboard') }}" class="btn btn-light px-4">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-person-check me-2"></i>Register Patient
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
