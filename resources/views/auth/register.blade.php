@extends('layouts.app')
@section('title', 'Register')

@section('content')
@include('partials.breadcrumbs', ['title' => 'Register'])

<section class="login register section">
    <div class="container">
        <div class="inner">
            <div class="row no-gutters">
                <div class="col-lg-6"><div class="login-left"></div></div>
                <div class="col-lg-6">
                    <div class="login-form">
                        <h2>Create Patient Account</h2>
                        <p>Already have an account?<a href="{{ route('login') }}">Login here</a></p>
                        <form class="form" method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <input type="text" name="name" placeholder="Full name" value="{{ old('name') }}" required>
                                        @error('name')<small class="mc-error">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                                        @error('email')<small class="mc-error">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="text" name="phone" placeholder="Phone (+255…)" value="{{ old('phone') }}" required>
                                        @error('phone')<small class="mc-error">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="password" name="password" placeholder="Password (min 8)" required>
                                        @error('password')<small class="mc-error">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="password" name="password_confirmation" placeholder="Confirm password" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group login-btn"><button class="btn" type="submit">Register</button></div>
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
