@php($user = auth()->user())
<header class="header">
    <div class="topbar">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-5 col-12">
                    <ul class="top-link">
                        <li><a href="{{ route('about') }}">About</a></li>
                        <li><a href="{{ route('doctors.index') }}">Doctors</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-6 col-md-7 col-12">
                    <ul class="top-contact">
                        <li><i class="fa fa-phone"></i>+255 700 000 000</li>
                        <li><i class="fa fa-envelope"></i><a href="mailto:support@mediconnect.co.tz">support@mediconnect.co.tz</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="header-inner">
        <div class="container">
            <div class="inner">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-12">
                        <div class="logo">
                            <a href="{{ route('home') }}" class="mc-logo">Medi<span>Connect</span></a>
                        </div>
                        <div class="mobile-nav"></div>
                    </div>
                    <div class="col-lg-6 col-md-9 col-12">
                        <div class="main-menu">
                            <nav class="navigation">
                                <ul class="nav menu">
                                    <li class="{{ request()->routeIs('home') ? 'active' : '' }}"><a href="{{ route('home') }}">Home</a></li>
                                    <li class="{{ request()->routeIs('doctors.*') ? 'active' : '' }}"><a href="{{ route('doctors.index') }}">Doctors</a></li>
                                    <li class="{{ request()->routeIs('hospitals.*') ? 'active' : '' }}"><a href="{{ route('hospitals.index') }}">Hospitals</a></li>
                                    <li class="{{ request()->routeIs('about') ? 'active' : '' }}"><a href="{{ route('about') }}">About Us</a></li>
                                    <li class="{{ request()->routeIs('afya-smart') ? 'active' : '' }}"><a href="{{ route('afya-smart') }}">Afya Smart</a></li>
                                    <li class="{{ request()->routeIs('contact') ? 'active' : '' }}"><a href="{{ route('contact') }}">Contact Us</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="col-lg-3 col-12">
                        <div class="get-quote mc-header-actions">
                            @if (! $user || $user->role === \App\Enums\UserRole::Patient)
                                <a href="{{ route('appointments.create') }}" class="btn">Book Appointment</a>
                            @endif
                            @auth
                                @if ($user->role !== \App\Enums\UserRole::Patient)
                                    <a href="{{ route('dashboard') }}" class="btn">Dashboard</a>
                                @else
                                    <a href="{{ route('dashboard') }}" class="mc-auth-link">Dashboard</a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}" class="mc-inline-form">
                                    @csrf
                                    <button type="submit" class="mc-auth-link">Logout</button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="mc-auth-link">Login</a>
                                <span class="mc-auth-sep">|</span>
                                <a href="{{ route('register') }}" class="mc-auth-link">Register</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
