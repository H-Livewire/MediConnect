@extends('layouts.auth')
@section('title', 'Register your hospital')
@section('card_class', 'mc-auth-wide')

@section('content')
<section class="mc-form-panel">
    <h1>Register your hospital</h1>
    <p class="mc-auth-sub">Start your {{ config('mediconnect.trial_days') }}-day free trial. No payment needed.</p>

    <form method="POST" action="{{ route('hospital.register.store') }}" class="mc-auth-form" id="hospital-register" novalidate>
        @csrf

        <h3 class="mc-form-step"><span>1</span> Hospital details</h3>
        <div class="mc-field">
            <label for="hospital_name">Hospital name</label>
            <input id="hospital_name" type="text" name="hospital_name" value="{{ old('hospital_name') }}" placeholder="e.g. Kigamboni Community Hospital" required>
            @error('hospital_name')<small class="mc-error">{{ $message }}</small>@enderror
        </div>
        <div class="mc-two">
            <div class="mc-field">
                <label for="registration_number">Registration number</label>
                <input id="registration_number" type="text" name="registration_number" value="{{ old('registration_number') }}" placeholder="Licence / registration no." required>
                @error('registration_number')<small class="mc-error">{{ $message }}</small>@enderror
            </div>
            <div class="mc-field">
                <label for="city">City</label>
                <input id="city" type="text" name="city" value="{{ old('city') }}" placeholder="Dar es Salaam" required>
                @error('city')<small class="mc-error">{{ $message }}</small>@enderror
            </div>
        </div>
        <div class="mc-field">
            <label for="address">Address</label>
            <input id="address" type="text" name="address" value="{{ old('address') }}" placeholder="Street, area" required>
            @error('address')<small class="mc-error">{{ $message }}</small>@enderror
        </div>
        <div class="mc-two">
            <div class="mc-field">
                <label for="hospital_phone">Hospital phone</label>
                <input id="hospital_phone" type="text" name="hospital_phone" value="{{ old('hospital_phone') }}" placeholder="+255…" required>
                @error('hospital_phone')<small class="mc-error">{{ $message }}</small>@enderror
            </div>
            <div class="mc-field">
                <label for="hospital_email">Hospital email <span class="mc-opt">(optional)</span></label>
                <input id="hospital_email" type="email" name="hospital_email" value="{{ old('hospital_email') }}" placeholder="info@hospital.com">
                @error('hospital_email')<small class="mc-error">{{ $message }}</small>@enderror
            </div>
        </div>
        <div class="mc-field">
            <label for="website">Website <span class="mc-opt">(optional)</span></label>
            <input id="website" type="text" name="website" value="{{ old('website') }}" placeholder="https://">
            @error('website')<small class="mc-error">{{ $message }}</small>@enderror
        </div>
        <div class="mc-field">
            <label for="description">About the hospital <span class="mc-opt">(optional)</span></label>
            <textarea id="description" name="description" rows="3" maxlength="1000" placeholder="Services, specialties, opening hours…">{{ old('description') }}</textarea>
            @error('description')<small class="mc-error">{{ $message }}</small>@enderror
        </div>

        <h3 class="mc-form-step"><span>2</span> Administrator account</h3>
        <div class="mc-field">
            <label for="admin_name">Your full name</label>
            <input id="admin_name" type="text" name="admin_name" value="{{ old('admin_name') }}" autocomplete="name" required>
            @error('admin_name')<small class="mc-error">{{ $message }}</small>@enderror
        </div>
        <div class="mc-two">
            <div class="mc-field">
                <label for="admin_email">Email (login)</label>
                <input id="admin_email" type="email" name="admin_email" value="{{ old('admin_email') }}" autocomplete="email" required>
                @error('admin_email')<small class="mc-error">{{ $message }}</small>@enderror
            </div>
            <div class="mc-field">
                <label for="admin_phone">Your phone</label>
                <input id="admin_phone" type="text" name="admin_phone" value="{{ old('admin_phone') }}" autocomplete="tel" required>
                @error('admin_phone')<small class="mc-error">{{ $message }}</small>@enderror
            </div>
        </div>
        <div class="mc-two">
            <div class="mc-field">
                <label for="password">Password</label>
                @include('partials.password-input', ['id' => 'password', 'name' => 'password', 'placeholder' => 'Min 8 characters', 'autocomplete' => 'new-password'])
                @error('password')<small class="mc-error">{{ $message }}</small>@enderror
            </div>
            <div class="mc-field">
                <label for="password_confirmation">Confirm password</label>
                @include('partials.password-input', ['id' => 'password_confirmation', 'name' => 'password_confirmation', 'placeholder' => 'Repeat password', 'autocomplete' => 'new-password'])
            </div>
        </div>

        <label class="mc-check mc-terms"><input type="checkbox" name="terms" value="1" @checked(old('terms'))> I confirm the details are correct and agree to the MediConnect terms.</label>
        @error('terms')<small class="mc-error">{{ $message }}</small>@enderror

        <button type="submit" class="btn mc-block mc-btn-loading" id="hospital-register-btn">Start free trial</button>
    </form>
    <p class="mc-switch">Already registered? <a href="{{ route('login') }}">Login</a></p>
</section>
@endsection

@push('scripts')
<script>
document.getElementById('hospital-register').addEventListener('submit', function () {
    var btn = document.getElementById('hospital-register-btn');
    btn.innerHTML = '<i class="mc-spinner" aria-hidden="true"></i> Creating your hospital…';
    btn.disabled = true;
});
</script>
@endpush
