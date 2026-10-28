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
    $me = auth()->user();
    $isSuper = $me->role === \App\Enums\UserRole::SuperAdmin;
    $myHospital = $isSuper ? null : $me->managedHospital;
    $homeRoute = $isSuper ? 'admin.dashboard' : 'hospital.dashboard';
    $pendingHospitals = $isSuper ? \App\Models\Hospital::whereNull('verified_at')->count() : 0;
    $nav = $isSuper ? [
        ['Overview', [['admin.dashboard', 'Dashboard', 'fa-th-large']]],
        ['Management', [
            ['admin.hospitals', 'Hospitals', 'fa-hospital-o', $pendingHospitals],
            ['admin.doctors', 'Doctors', 'fa-user-md'],
            ['admin.appointments', 'Appointments', 'fa-calendar-check-o'],
            ['admin.users', 'Users', 'fa-users'],
        ]],
        ['Engagement', [['admin.messages', 'Messages', 'fa-envelope-o']]],
    ] : [
        ['Overview', [['hospital.dashboard', 'Dashboard', 'fa-th-large']]],
        ['Manage', [
            ['hospital.doctors.index', 'Doctors', 'fa-user-md'],
            ['hospital.appointments', 'Appointments', 'fa-calendar-check-o'],
        ]],
        ['Hospital', [
            ['hospital.profile', 'Profile', 'fa-hospital-o'],
            ['hospital.subscription', 'Subscription', 'fa-credit-card'],
        ]],
    ];
    $initials = collect(explode(' ', $me->name))->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->implode('');
    $unread = $me->unreadNotifications()->count();
    $latest = $me->notifications()->latest()->take(6)->get();
@endphp
<div class="ad-shell" id="ad-shell">
    <aside class="ad-sidebar" id="ad-sidebar">
        <a href="{{ route($homeRoute) }}" class="ad-brand"><span class="ad-mark">M</span><span>MediConnect</span></a>
        <nav class="ad-nav">
            @foreach ($nav as [$label, $items])
                <div class="ad-nav-label">{{ $label }}</div>
                @foreach ($items as $item)
                    @php
                        [$route, $text, $icon] = $item;
                        $pattern = str_ends_with($route, '.index') ? substr($route, 0, -5).'*' : $route.'*';
                    @endphp
                    <a href="{{ route($route) }}" class="{{ request()->routeIs($pattern) ? 'is-active' : '' }}">
                        <i class="fa {{ $icon }}"></i>{{ $text }}
                        @if (! empty($item[3]))<span class="ad-count">{{ $item[3] }}</span>@endif
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
            @if ($isSuper)
            <form class="ad-search" method="GET" action="{{ route('admin.users') }}" role="search">
                <i class="fa fa-search"></i>
                <input type="search" name="q" placeholder="Search users by name or email…" value="{{ request()->routeIs('admin.users') ? request('q') : '' }}">
            </form>
            @endif
            <div class="ad-top-actions">
                <details class="ad-user ad-bell">
                    <summary class="ad-icon-btn" aria-label="Notifications">
                        <i class="fa fa-bell-o"></i>
                        @if ($unread)<span class="ad-bell-dot">{{ $unread > 9 ? '9+' : $unread }}</span>@endif
                    </summary>
                    <div class="ad-menu-pop ad-notif">
                        <div class="ad-notif-head">
                            <strong>Notifications</strong>
                            @if ($unread)
                                <form method="POST" action="{{ route('notifications.read-all') }}">@csrf<button type="submit" class="ad-link">Mark all read</button></form>
                            @endif
                        </div>
                        @forelse ($latest as $note)
                            <a href="{{ route('notifications.open', $note->id) }}" class="ad-notif-item {{ $note->read_at ? '' : 'is-unread' }}">
                                <i class="fa {{ $note->data['icon'] ?? 'fa-bell' }}"></i>
                                <span><b>{{ $note->data['title'] ?? 'Notification' }}</b><small>{{ \Illuminate\Support\Str::limit($note->data['body'] ?? '', 90) }}</small><em>{{ $note->created_at->diffForHumans() }}</em></span>
                            </a>
                        @empty
                            <p class="ad-notif-empty">You're all caught up.</p>
                        @endforelse
                    </div>
                </details>
                @include('partials.theme-toggle')
                <details class="ad-user">
                    <summary>
                        <span class="ad-avatar">{{ $initials }}</span>
                        <span class="ad-user-meta"><strong>{{ $me->name }}</strong><small>{{ $me->role->label() }}</small></span>
                    </summary>
                    <div class="ad-menu-pop">
                        <a href="{{ route('home') }}"><i class="fa fa-globe"></i>Visit website</a>
                        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit"><i class="fa fa-sign-out"></i>Logout</button></form>
                    </div>
                </details>
            </div>
        </header>

        <main class="ad-content">
            @if ($myHospital)
                @if ($myHospital->hasAccess() && ! $myHospital->subscriptionActive())
                    <div class="ad-banner is-info"><i class="fa fa-clock-o"></i><span><b>Free trial: {{ $myHospital->daysLeft() }} {{ \Illuminate\Support\Str::plural('day', $myHospital->daysLeft()) }} left.</b> Everything is unlocked until {{ $myHospital->accessEndsAt()->format('d M Y') }}.</span><a href="{{ route('hospital.subscription') }}" class="ad-btn ad-btn-sm">Subscription</a></div>
                @elseif (! $myHospital->hasAccess())
                    <div class="ad-banner is-warn"><i class="fa fa-exclamation-triangle"></i><span><b>{{ $myHospital->stateLabel() }}.</b> {{ $myHospital->blockedReason() }}</span><a href="{{ route('hospital.subscription') }}" class="ad-btn ad-btn-sm">Details</a></div>
                @endif
                @if ($myHospital->is_active && ! $myHospital->isVerified())
                    <div class="ad-banner is-info"><i class="fa fa-shield"></i><span><b>Waiting for verification.</b> Patients will see your hospital as soon as MediConnect verifies it.</span></div>
                @endif
            @endif
            @if (session('status'))<div class="ad-flash">{{ session('status') }}</div>@endif
            @if (session('warning'))<div class="ad-banner is-warn"><i class="fa fa-exclamation-triangle"></i><span>{{ session('warning') }}</span></div>@endif
            @if ($errors->any() && $isSuper)<div class="ad-banner is-warn"><i class="fa fa-exclamation-triangle"></i><span>{{ $errors->first() }}</span></div>@endif
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
    document.querySelectorAll('[data-spinner-form]').forEach(function (form) {
        form.addEventListener('submit', function () {
            var btn = form.querySelector('button[type="submit"]');
            if (btn) { btn.innerHTML = '<i class="ad-spin"></i> Please wait…'; btn.disabled = true; }
        });
    });
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
