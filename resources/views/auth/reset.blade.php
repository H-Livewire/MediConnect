@extends('layouts.auth')
@section('title', 'Reset Password')

@section('content')
<section class="mc-form-panel">
    <h1>Reset password</h1>
    <p class="mc-auth-sub">Choose a new password for your account.</p>
    <form method="POST" action="{{ route('password.update') }}" class="mc-auth-form" novalidate>
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="mc-field">
            <label for="r-email">Email</label>
            <input id="r-email" type="email" name="email" value="{{ old('email', $email) }}" required autocomplete="email">
            @error('email')<small class="mc-error">{{ $message }}</small>@enderror
        </div>
        <div class="mc-field">
            <label for="r-password">New password</label>
            <input id="r-password" type="password" name="password" placeholder="Min 8 characters" required autocomplete="new-password">
            @error('password')<small class="mc-error">{{ $message }}</small>@enderror
        </div>
        <div class="mc-field">
            <label for="r-password2">Confirm password</label>
            <input id="r-password2" type="password" name="password_confirmation" required autocomplete="new-password">
        </div>
        <button type="submit" class="btn mc-block">Reset password</button>
    </form>
    <p class="mc-switch"><a href="{{ route('login') }}"><i class="fa fa-angle-left"></i> Back to login</a></p>
</section>
@endsection
