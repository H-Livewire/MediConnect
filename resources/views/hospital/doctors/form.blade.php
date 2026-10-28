@extends('layouts.admin')
@section('title', $doctor ? 'Edit doctor' : 'Add doctor')

@section('content')
@php
    $field = fn (string $key, $default = '') => old($key, $default);
@endphp
<div class="ad-crumbs">Home <span>/</span> <a href="{{ route('hospital.doctors.index') }}">Doctors</a> <span>/</span> {{ $doctor ? 'Edit' : 'Add' }}</div>
<div class="ad-head"><div><h1>{{ $doctor ? 'Edit '.$doctor->user->name : 'Add a doctor' }}</h1><p>{{ $doctor ? 'Update details or reset the password.' : 'Create the doctor\'s account and profile. Share the email and password with the doctor.' }}</p></div></div>

<div class="ad-card">
    <div class="ad-card-body">
        <form method="POST" enctype="multipart/form-data" class="ad-form" data-spinner-form
              action="{{ $doctor ? route('hospital.doctors.update', $doctor) : route('hospital.doctors.store') }}">
            @csrf
            @if ($doctor) @method('PUT') @endif

            <div class="ad-grid-2">
                <div class="ad-field">
                    <label for="name">Full name</label>
                    <input id="name" type="text" name="name" value="{{ $field('name', $doctor?->user->name) }}" placeholder="Dr. Jane Doe" required>
                    @error('name')<span class="ad-error">{{ $message }}</span>@enderror
                </div>
                <div class="ad-field">
                    <label for="email">Email (login)</label>
                    <input id="email" type="email" name="email" value="{{ $field('email', $doctor?->user->email) }}" placeholder="doctor@example.com" required>
                    @error('email')<span class="ad-error">{{ $message }}</span>@enderror
                </div>
                <div class="ad-field">
                    <label for="phone">Phone</label>
                    <input id="phone" type="text" name="phone" value="{{ $field('phone', $doctor?->user->phone) }}" placeholder="+255…" required>
                    @error('phone')<span class="ad-error">{{ $message }}</span>@enderror
                </div>
                <div class="ad-field">
                    <label for="password">{{ $doctor ? 'New password (optional)' : 'Initial password' }}</label>
                    <input id="password" type="password" name="password" placeholder="Min 8 characters" autocomplete="new-password" @required(! $doctor)>
                    @error('password')<span class="ad-error">{{ $message }}</span>@enderror
                </div>
                <div class="ad-field">
                    <label for="specialty_id">Specialty</label>
                    <select id="specialty_id" name="specialty_id">
                        <option value="">Select a specialty…</option>
                        @foreach ($specialties as $specialty)
                            <option value="{{ $specialty->id }}" @selected((string) $field('specialty_id', $doctor?->specialty_id) === (string) $specialty->id)>{{ $specialty->name }}</option>
                        @endforeach
                    </select>
                    @error('specialty_id')<span class="ad-error">{{ $message }}</span>@enderror
                </div>
                <div class="ad-field">
                    <label for="new_specialty">…or add a new specialty</label>
                    <input id="new_specialty" type="text" name="new_specialty" value="{{ $field('new_specialty') }}" placeholder="e.g. Dermatology">
                    @error('new_specialty')<span class="ad-error">{{ $message }}</span>@enderror
                </div>
                <div class="ad-field">
                    <label for="experience_years">Experience (years)</label>
                    <input id="experience_years" type="number" min="0" max="70" name="experience_years" value="{{ $field('experience_years', $doctor?->experience_years ?? 0) }}" required>
                    @error('experience_years')<span class="ad-error">{{ $message }}</span>@enderror
                </div>
                <div class="ad-field">
                    <label for="consultation_fee">Consultation fee (TZS)</label>
                    <input id="consultation_fee" type="number" min="0" step="1000" name="consultation_fee" value="{{ $field('consultation_fee', $doctor?->consultation_fee ?? 30000) }}" required>
                    @error('consultation_fee')<span class="ad-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="ad-field">
                <label for="bio">Short bio</label>
                <textarea id="bio" name="bio" rows="3" maxlength="1000" placeholder="Qualifications, areas of focus…">{{ $field('bio', $doctor?->bio) }}</textarea>
                @error('bio')<span class="ad-error">{{ $message }}</span>@enderror
            </div>

            <div class="ad-field">
                <label for="photo">Photo</label>
                @if ($doctor)<img class="ad-preview" src="{{ $doctor->photoUrl() }}" alt="">@endif
                <input id="photo" type="file" name="photo" accept="image/png,image/jpeg,image/webp">
                <small>JPG, PNG or WebP, up to 2 MB.</small>
                @error('photo')<span class="ad-error">{{ $message }}</span>@enderror
            </div>

            <div class="ad-form-actions">
                <button type="submit" class="ad-btn ad-btn-primary" data-label="{{ $doctor ? 'Save changes' : 'Add doctor' }}">{{ $doctor ? 'Save changes' : 'Add doctor' }}</button>
                <a href="{{ route('hospital.doctors.index') }}" class="ad-btn">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
