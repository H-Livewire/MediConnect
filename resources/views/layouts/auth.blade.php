<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    @include('partials.theme-init')
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Get Started') | MediConnect</title>
    <link rel="icon" href="{{ asset('mediplus/img/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('mediplus/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('mediplus/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('mediplus/css/icofont.css') }}">
    <link rel="stylesheet" href="{{ asset('mediplus/style.css') }}">
    <link rel="stylesheet" href="{{ asset('mediplus/mediconnect.css') }}">
    <link rel="stylesheet" href="{{ asset('mediplus/mediconnect-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('mediplus/mediconnect-dark.css') }}">
</head>
<body class="mc-auth-body">
    <div class="mc-auth">
        <aside class="mc-auth-visual" style="background-image: url('{{ asset('mediplus/img/login-bg.jpg') }}')" role="img" aria-label="Healthcare illustration"></aside>
        <main class="mc-auth-panel">
            <div class="mc-auth-top">
                <a href="{{ route('home') }}" class="mc-auth-back"><i class="fa fa-angle-left"></i> Back to home</a>
                @include('partials.theme-toggle')
            </div>
            <div class="mc-auth-card">
                <a href="{{ route('home') }}" class="mc-logo mc-auth-logo">Medi<span>Connect</span></a>
                @yield('content')
            </div>
        </main>
    </div>
    @include('partials.theme-script')
    @stack('scripts')
</body>
</html>
