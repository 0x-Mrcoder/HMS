@extends('layouts.auth')

@section('title', $portalConfig['label'] . ' Login')

@section('content')
<div class="container-xxl">
    <div class="row vh-100 d-flex justify-content-center">
        <div class="col-12 align-self-center">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4 mx-auto">
                        <div class="card">
                            <div class="card-body p-0 bg-black auth-header-box rounded-top">
                                <div class="text-center p-3">
                                    <a href="{{ route('dashboard') }}" class="logo logo-admin">
                                        <img src="{{ asset('rizz-assets/images/logo-sm.png') }}" height="50" alt="logo" class="auth-logo">
                                    </a>
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-{{ $portalConfig['accent'] ?? 'primary' }}-subtle text-{{ $portalConfig['accent'] ?? 'primary' }} mb-2" style="width:56px;height:56px;">
                                        <i class="{{ $portalConfig['icon'] ?? 'iconoir-shield' }} fs-24"></i>
                                    </div>
                                    <h4 class="mt-2 mb-1 fw-semibold text-white fs-18">{{ $portalConfig['label'] }}</h4>
                                    <p class="text-muted fw-medium mb-0">{{ $portalConfig['tagline'] }}</p>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="alert alert-info border-info d-flex align-items-center gap-2">
                                    <i class="bi bi-info-circle fs-4"></i>
                                    <span>Demo credentials: <strong>{{ $portal }}@hms.com</strong> / <strong>123456</strong></span>
                                </div>
                                <form class="my-4" method="POST" action="{{ route('portal.login.' . $portal . '.store') }}">
                                    @csrf
                                    <div class="form-group mb-2">
                                        <label class="form-label" for="username">Email address</label>
                                        <input type="email" class="form-control" id="username" name="email" value="{{ old('email') }}" placeholder="Enter email" required autofocus>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="userpassword">Password</label>
                                        <input type="password" class="form-control" name="password" id="userpassword" placeholder="Enter password" required>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <div class="col-sm-6">
                                            <div class="form-check form-switch form-switch-success">
                                                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                                <label class="form-check-label" for="remember">Remember me</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 text-end">
                                            <a href="javascript:void(0);" class="text-muted font-13"><i class="dripicons-lock"></i> Forgot password?</a>
                                        </div>
                                    </div>
                                    <div class="form-group mb-0 row">
                                        <div class="col-12">
                                            <div class="d-grid mt-3">
                                                <button class="btn btn-primary" type="submit">Log In <i class="fas fa-sign-in-alt ms-1"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="text-center mb-2">
                                    <p class="text-muted mb-0">All HMS logins share the same credentials format.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
