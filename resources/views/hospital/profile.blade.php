@extends('layouts.admin')
@section('title', 'Hospital profile')

@section('content')
<div class="ad-crumbs">Home <span>/</span> Profile</div>
<div class="ad-head"><div><h1>Hospital profile</h1><p>This is what patients see on your public page.</p></div>
    @if ($hospital->isVisible())<a href="{{ route('hospitals.show', $hospital) }}" class="ad-btn" target="_blank" rel="noopener"><i class="fa fa-external-link"></i>View public page</a>@endif
</div>

<div class="ad-card">
    <div class="ad-card-body">
        <form method="POST" action="{{ route('hospital.profile.update') }}" enctype="multipart/form-data" class="ad-form" data-spinner-form>
            @csrf @method('PUT')
            <div class="ad-grid-2">
                <div class="ad-field">
                    <label for="name">Hospital name</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $hospital->name) }}" required>
                    @error('name')<span class="ad-error">{{ $message }}</span>@enderror
                </div>
                <div class="ad-field">
                    <label for="registration_number">Registration number</label>
                    <input id="registration_number" type="text" name="registration_number" value="{{ old('registration_number', $hospital->registration_number) }}" required>
                    @error('registration_number')<span class="ad-error">{{ $message }}</span>@enderror
                </div>
                <div class="ad-field">
                    <label for="city">City</label>
                    <input id="city" type="text" name="city" value="{{ old('city', $hospital->city) }}" required>
                    @error('city')<span class="ad-error">{{ $message }}</span>@enderror
                </div>
                <div class="ad-field">
                    <label for="address">Address</label>
                    <input id="address" type="text" name="address" value="{{ old('address', $hospital->address) }}" required>
                    @error('address')<span class="ad-error">{{ $message }}</span>@enderror
                </div>
                <div class="ad-field">
                    <label for="phone">Phone</label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone', $hospital->phone) }}" required>
                    @error('phone')<span class="ad-error">{{ $message }}</span>@enderror
                </div>
                <div class="ad-field">
                    <label for="email">Public email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $hospital->email) }}">
                    @error('email')<span class="ad-error">{{ $message }}</span>@enderror
                </div>
                <div class="ad-field">
                    <label for="website">Website</label>
                    <input id="website" type="url" name="website" value="{{ old('website', $hospital->website) }}" placeholder="https://">
                    @error('website')<span class="ad-error">{{ $message }}</span>@enderror
                </div>
                <div class="ad-field">
                    <label for="image">Logo / photo</label>
                    <input id="image" type="file" name="image" accept="image/png,image/jpeg,image/webp">
                    @error('image')<span class="ad-error">{{ $message }}</span>@enderror
                </div>
            </div>
            @if ($hospital->image)<img class="ad-preview" src="{{ $hospital->imageUrl() }}" alt="">@endif

            <div class="ad-field">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4" maxlength="1000">{{ old('description', $hospital->description) }}</textarea>
                @error('description')<span class="ad-error">{{ $message }}</span>@enderror
            </div>
            <div class="ad-grid-2">
                <div class="ad-field">
                    <label for="services">Services</label>
                    <textarea id="services" name="services" rows="3" placeholder="Laboratory, X-ray, Dental, Maternity">{{ old('services', implode(', ', $hospital->services ?? [])) }}</textarea>
                    <small>Separate with commas.</small>
                    @error('services')<span class="ad-error">{{ $message }}</span>@enderror
                </div>
                <div class="ad-field">
                    <label for="insurances">Accepted insurance</label>
                    <textarea id="insurances" name="insurances" rows="3" placeholder="NHIF, AAR, Jubilee">{{ old('insurances', implode(', ', $hospital->insurances ?? [])) }}</textarea>
                    <small>Separate with commas.</small>
                    @error('insurances')<span class="ad-error">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="ad-form-actions">
                <button type="submit" class="ad-btn ad-btn-primary" data-label="Save profile">Save profile</button>
            </div>
        </form>
    </div>
</div>
@endsection
