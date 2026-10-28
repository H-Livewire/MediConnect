<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    @include('partials.theme-init')
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | MediConnect Admin</title>
    <link rel="icon" href="{{ asset('mediplus/img/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('mediplus/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('mediplus/admin.css') }}">
</head>
<body class="ad-body">
@php
    $nav = [
        ['Overview', [['admin.dashboard', 'Dashboard', 'fa-th-large']]],
        ['Management', [
            ['admin.hospitals', 'Hospitals', 'fa-hospital-o'],
            ['admin.doctors', 'Doctors', 'fa-user-md'],
            ['admin.appointments', 'Appointments', 'fa-calendar-check-o'],
            ['admin.users', 'Users', 'fa-users'],
        ]],
        ['Engagement', [['admin.messages', 'Messages', 'fa-envelope-o']]],
    ];
    $initials = collect(explode(' ', auth()->user()->name))->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->implode('');
@endphp
<div class="ad-shell" id="ad-shell">
    <aside class="ad-sidebar" id="ad-sidebar">
        <a href="{{ route('admin.dashboard') }}" class="ad-brand"><span class="ad-mark">M</span><span>MediConnect</span></a>
        <nav class="ad-nav">
            @foreach ($nav as [$label, $items])
                <div class="ad-nav-label">{{ $label }}</div>
                @foreach ($items as [$route, $text, $icon])
                    <a href="{{ route($route) }}" class="{{ request()->routeIs($route.'*') && ($route !== 'admin.dashboard' || request()->routeIs('admin.dashboard')) ? 'is-active' : '' }}">
                        <i class="fa {{ $icon }}"></i>{{ $text }}
                    </a>
                @endforeach
            @endforeach
            <div class="ad-nav-label">Website</div>
            <a href="{{ route('home') }}" target="_blank" rel="noopener"><i class="fa fa-external-link"></i>View site</a>
        </nav>
    </aside>
    <div class="ad-backdrop" data-ad-close></div>

    <div class="ad-main">
        <header class="ad-topbar">
            <button type="button" class="ad-icon-btn ad-menu" data-ad-toggle aria-label="Open menu"><i class="fa fa-bars"></i></button>
            <form class="ad-search" method="GET" action="{{ route('admin.users') }}" role="search">
                <i class="fa fa-search"></i>
                <input type="search" name="q" placeholder="Search users by name or email…" value="{{ request()->routeIs('admin.users') ? request('q') : '' }}">
            </form>
            <div class="ad-top-actions">
                @include('partials.theme-toggle')
                <details class="ad-user">
                    <summary>
                        <span class="ad-avatar">{{ $initials }}</span>
                        <span class="ad-user-meta"><strong>{{ auth()->user()->name }}</strong><small>Super Admin</small></span>
                    </summary>
                    <div class="ad-menu-pop">
                        <a href="{{ route('home') }}"><i class="fa fa-globe"></i>Visit website</a>
                        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit"><i class="fa fa-sign-out"></i>Logout</button></form>
                    </div>
                </details>
            </div>
        </header>

        <main class="ad-content">
            @if (session('status'))<div class="ad-flash">{{ session('status') }}</div>@endif
            @yield('content')
        </main>
    </div>
</div>
@include('partials.theme-script')
<script>
(function () {
    var shell = document.getElementById('ad-shell');
    document.querySelectorAll('[data-ad-toggle]').forEach(function (b) { b.addEventListener('click', function () { shell.classList.toggle('is-open'); }); });
    document.querySelectorAll('[data-ad-close]').forEach(function (b) { b.addEventListener('click', function () { shell.classList.remove('is-open'); }); });
    document.querySelectorAll('[data-range-tabs]').forEach(function (group) {
        var tabs = group.querySelectorAll('[data-range]');
        var panes = group.parentElement.querySelectorAll('[data-pane]');
        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                tabs.forEach(function (t) { t.classList.toggle('is-active', t === tab); });
                panes.forEach(function (p) { p.hidden = p.getAttribute('data-pane') !== tab.getAttribute('data-range'); });
            });
        });
    });
})();
</script>
</body>
</html>
