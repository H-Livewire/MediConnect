<!doctype html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MediConnect') | MediConnect</title>
    <link rel="icon" href="{{ asset('mediplus/img/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css?family=Poppins:200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('mediplus/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('mediplus/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('mediplus/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('mediplus/css/icofont.css') }}">
    <link rel="stylesheet" href="{{ asset('mediplus/css/slicknav.min.css') }}">
    <link rel="stylesheet" href="{{ asset('mediplus/css/owl-carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('mediplus/css/datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('mediplus/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('mediplus/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('mediplus/css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('mediplus/style.css') }}">
    <link rel="stylesheet" href="{{ asset('mediplus/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('mediplus/mediconnect.css') }}">
    @stack('styles')
</head>
<body>
    <div class="preloader">
        <div class="loader">
            <div class="loader-outter"></div>
            <div class="loader-inner"></div>
            <div class="indicator">
                <svg width="16px" height="12px">
                    <polyline id="back" points="1 6 4 6 6 11 10 1 12 6 15 6"></polyline>
                    <polyline id="front" points="1 6 4 6 6 11 10 1 12 6 15 6"></polyline>
                </svg>
            </div>
        </div>
    </div>

    @include('partials.header')

    @if (session('status'))
        <div class="container mc-flash"><div class="mc-alert mc-alert-success">{{ session('status') }}</div></div>
    @endif

    @yield('content')

    @include('partials.footer')

    <script src="{{ asset('mediplus/js/jquery.min.js') }}"></script>
    <script src="{{ asset('mediplus/js/jquery-migrate-3.0.0.js') }}"></script>
    <script src="{{ asset('mediplus/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('mediplus/js/easing.js') }}"></script>
    <script src="{{ asset('mediplus/js/popper.min.js') }}"></script>
    <script src="{{ asset('mediplus/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('mediplus/js/jquery.nav.js') }}"></script>
    <script src="{{ asset('mediplus/js/slicknav.min.js') }}"></script>
    <script src="{{ asset('mediplus/js/jquery.scrollUp.min.js') }}"></script>
    <script src="{{ asset('mediplus/js/niceselect.js') }}"></script>
    <script src="{{ asset('mediplus/js/tilt.jquery.min.js') }}"></script>
    <script src="{{ asset('mediplus/js/owl-carousel.js') }}"></script>
    <script src="{{ asset('mediplus/js/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('mediplus/js/steller.js') }}"></script>
    <script src="{{ asset('mediplus/js/wow.min.js') }}"></script>
    <script src="{{ asset('mediplus/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.3/waypoints.min.js"></script>
    <script src="{{ asset('mediplus/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('mediplus/js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>
