<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-startbar="light">

<head>
    @php
        $hospitalName = $hospitalConfig['name'] ?? 'Hospital';
        $hospitalTagline = $hospitalConfig['tagline'] ?? 'Pharmacy portal';
        $hospitalLogo = $hospitalConfig['logo_path'] ? asset($hospitalConfig['logo_path']) : asset('rizz-assets/images/favicon.ico');
    @endphp
    <meta charset="utf-8" />
    <title>@yield('title', 'Pharmacy Portal') | {{ $hospitalName }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ $hospitalLogo }}">
    <link href="{{ asset('rizz-assets/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('rizz-assets/css/icons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('rizz-assets/css/app.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/iconoir@7.7.0/css/iconoir.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    @stack('styles')
</head>

<body class="@yield('body-class', 'pharmacy-portal')">
    @include('pharmacy.partials.topbar')
    @include('pharmacy.partials.startbar')

    <div class="page-wrapper">
        <div class="page-content">
            @yield('content')
            @include('layouts.partials.footer')
        </div>
    </div>

    <script src="{{ asset('rizz-assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('rizz-assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('rizz-assets/js/app.js') }}"></script>
    @stack('scripts')
</body>

</html>
