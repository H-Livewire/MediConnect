@extends('layouts.auth')
@section('title', $panel === 'register' ? 'Register' : ($panel === 'forgot' ? 'Forgot Password' : 'Login'))

@section('content')
<div class="mc-panels" id="mc-panels" data-active="{{ $panel }}">

    {{-- LOGIN --}}
    <section class="mc-form-panel" data-panel="login" @if ($panel !== 'login') hidden @endif>
        <h1>Welcome back</h1>
        <p class="mc-auth-sub">Login to manage your appointments.</p>
        @if (session('status') && $panel === 'login')
            <div class="mc-alert mc-alert-success">{{ session('status') }}</div>
        @endif
        <form method="POST" action="{{ route('login') }}" class="mc-auth-form" novalidate>
            @csrf
            <div class="mc-field">
                <label for="login-email">Email</label>
                <input id="login-email" type="email" name="email" value="{{ $panel === 'login' ? old('email') : '' }}" placeholder="you@example.com" autocomplete="email" required>
                @if ($errors->login->has('email'))<small class="mc-error">{{ $errors->login->first('email') }}</small>@endif
            </div>
            <div class="mc-field">
                <label for="login-password">Password</label>
                <input id="login-password" type="password" name="password" placeholder="Your password" autocomplete="current-password" required>
                @if ($errors->login->has('password'))<small class="mc-error">{{ $errors->login->first('password') }}</small>@endif
            </div>
            <div class="mc-row-between">
                <label class="mc-check"><input type="checkbox" name="remember" value="1"> Remember me</label>
                <a href="{{ route('password.request') }}" data-panel-target="forgot">Forgot password?</a>
            </div>
            <button type="submit" class="btn mc-block">Login</button>
        </form>
        <p class="mc-switch">Don't have an account? <a href="{{ route('register') }}" data-panel-target="register">Register</a></p>
    </section>

    {{-- REGISTER --}}
    <section class="mc-form-panel" data-panel="register" @if ($panel !== 'register') hidden @endif>
        <h1>Create your account</h1>
        <p class="mc-auth-sub">Register as a patient to start booking.</p>
        <form method="POST" action="{{ route('register') }}" class="mc-auth-form" novalidate>
            @csrf
            <div class="mc-field">
                <label for="reg-name">Full name</label>
                <input id="reg-name" type="text" name="name" value="{{ $panel === 'register' ? old('name') : '' }}" placeholder="Your full name" autocomplete="name" required>
                @if ($errors->register->has('name'))<small class="mc-error">{{ $errors->register->first('name') }}</small>@endif
            </div>
            <div class="mc-two">
                <div class="mc-field">
                    <label for="reg-email">Email</label>
                    <input id="reg-email" type="email" name="email" value="{{ $panel === 'register' ? old('email') : '' }}" placeholder="you@example.com" autocomplete="email" required>
                    @if ($errors->register->has('email'))<small class="mc-error">{{ $errors->register->first('email') }}</small>@endif
                </div>
                <div class="mc-field">
                    <label for="reg-phone">Phone</label>
                    <input id="reg-phone" type="text" name="phone" value="{{ $panel === 'register' ? old('phone') : '' }}" placeholder="+255…" autocomplete="tel" required>
                    @if ($errors->register->has('phone'))<small class="mc-error">{{ $errors->register->first('phone') }}</small>@endif
                </div>
            </div>
            <div class="mc-two">
                <div class="mc-field">
                    <label for="reg-password">Password</label>
                    <input id="reg-password" type="password" name="password" placeholder="Min 8 characters" autocomplete="new-password" required>
                    @if ($errors->register->has('password'))<small class="mc-error">{{ $errors->register->first('password') }}</small>@endif
                </div>
                <div class="mc-field">
                    <label for="reg-password2">Confirm password</label>
                    <input id="reg-password2" type="password" name="password_confirmation" placeholder="Repeat password" autocomplete="new-password" required>
                </div>
            </div>
            <button type="submit" class="btn mc-block">Create account</button>
        </form>
        <p class="mc-switch">Already have an account? <a href="{{ route('login') }}" data-panel-target="login">Login</a></p>
    </section>

    {{-- FORGOT PASSWORD --}}
    <section class="mc-form-panel" data-panel="forgot" @if ($panel !== 'forgot') hidden @endif>
        <h1>Forgot password?</h1>
        <p class="mc-auth-sub">Enter your email and we will send you a link to reset it.</p>
        @if (session('status') && $panel === 'forgot')
            <div class="mc-alert mc-alert-success">{{ session('status') }}</div>
        @endif
        <form method="POST" action="{{ route('password.email') }}" class="mc-auth-form" novalidate>
            @csrf
            <div class="mc-field">
                <label for="forgot-email">Email</label>
                <input id="forgot-email" type="email" name="email" value="{{ $panel === 'forgot' ? old('email') : '' }}" placeholder="you@example.com" autocomplete="email" required>
                @if ($errors->forgot->has('email'))<small class="mc-error">{{ $errors->forgot->first('email') }}</small>@endif
            </div>
            <button type="submit" class="btn mc-block">Send reset link</button>
        </form>
        <p class="mc-switch"><a href="{{ route('login') }}" data-panel-target="login"><i class="fa fa-angle-left"></i> Back to login</a></p>
    </section>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var root = document.getElementById('mc-panels');
    var panels = root.querySelectorAll('[data-panel]');
    var urls = { login: "{{ route('login', [], false) }}", register: "{{ route('register', [], false) }}", forgot: "{{ route('password.request', [], false) }}" };
    var titles = { login: 'Login', register: 'Register', forgot: 'Forgot Password' };

    function show(name, push) {
        panels.forEach(function (p) { p.hidden = p.getAttribute('data-panel') !== name; });
        document.title = titles[name] + ' | MediConnect';
        if (push && window.history && history.replaceState) { history.replaceState({ panel: name }, '', urls[name]); }
        var first = root.querySelector('[data-panel="' + name + '"] input:not([type=hidden])');
        if (first) { first.focus({ preventScroll: true }); }
    }

    root.querySelectorAll('[data-panel-target]').forEach(function (a) {
        a.addEventListener('click', function (e) { e.preventDefault(); show(a.getAttribute('data-panel-target'), true); });
    });
})();
</script>
@endpush
