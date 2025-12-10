<!DOCTYPE html>
<html lang="en" dir="ltr" data-startbar="light" data-bs-theme="light">

<head>
    @php
        $hospitalConfig = config('hms.hospital', []);
        $hospitalName = $hospitalConfig['name'] ?? 'Hospital';
        $hospitalTagline = $hospitalConfig['tagline'] ?? 'Laboratory Portal';
        $hospitalLogo = ($hospitalConfig['logo_path'] ?? null) ? asset($hospitalConfig['logo_path']) : asset('rizz-assets/images/favicon.ico');
    @endphp
    <meta charset="utf-8" />
    <title>@yield('title', 'Lab Portal') | {{ $hospitalName }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="{{ $hospitalTagline }}" name="description" />
    <meta name="author" content="" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="{{ $hospitalLogo }}">
    <link href="{{ asset('rizz-assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('rizz-assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('rizz-assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/iconoir@7.7.0/css/iconoir.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    @stack('styles')
</head>

<body id="body" class="@yield('body-class', 'lab-portal')">
    @include('lab.partials.topbar')
    @include('lab.partials.startbar')

    <div class="page-wrapper">
        <div class="page-content">
            @if (isset($header))
                <div class="container-xxl">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <h4 class="mb-0 fw-semibold">{{ $header }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="container-xxl">
                {{ $slot }}
            </div>
            
            @include('layouts.partials.footer')
        </div>
    </div>

    <script src="{{ asset('rizz-assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('rizz-assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('rizz-assets/js/app.js') }}"></script>
    @stack('scripts')
</body>

</html>
