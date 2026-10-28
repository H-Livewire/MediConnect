@php($user = auth()->user())
<header class="header">
    <div class="header-inner">
        <div class="container">
            <div class="inner mc-header-row">
                <div class="logo mc-header-logo">
                    <a href="{{ route('home') }}" class="mc-logo">Medi<span>Connect</span></a>
                </div>

                <div class="main-menu mc-main-menu">
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

                <div class="mc-header-end">
                    @include('partials.theme-toggle')
                    <div class="get-quote">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn mc-btn-arrow">Get Started <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                        @endauth
                    </div>
                    @auth
                        <form method="POST" action="{{ route('logout') }}" class="mc-inline-form">
                            @csrf
                            <button type="submit" class="mc-auth-link">Logout</button>
                        </form>
                    @endauth
                    <div class="mobile-nav"></div>
                </div>
            </div>
        </div>
    </div>
</header>
