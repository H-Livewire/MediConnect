@extends('layouts.app')
@section('title', 'Login')

@section('content')
@include('partials.breadcrumbs', ['title' => 'Login'])

<section class="login section">
    <div class="container">
        <div class="inner">
            <div class="row no-gutters">
                <div class="col-lg-6"><div class="login-left"></div></div>
                <div class="col-lg-6">
                    <div class="login-form">
                        <h2>Login Now</h2>
                        <p>Don't have an account?<a href="{{ route('register') }}">Register here</a></p>
                        <form class="form" method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>
                                        @error('email')<small class="mc-error">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <input type="password" name="password" placeholder="Password" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group login-btn"><button class="btn" type="submit">Login</button></div>
                                    <div class="checkbox">
                                        <label class="checkbox-inline"><input name="remember" type="checkbox" value="1">Remember me</label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
