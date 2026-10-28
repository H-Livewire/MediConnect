@extends('layouts.app')
@section('title', 'Hospital Dashboard')

@section('content')
@include('partials.breadcrumbs', ['title' => 'Hospital Dashboard'])

<section class="mc-dashboard section">
    <div class="container">
        @if ($hospital)
            <div class="mc-dash-head"><div><h3>{{ $hospital->name }}</h3><p class="mc-muted">{{ $hospital->city }}</p></div></div>
            <h4 class="mc-subtitle">Doctors ({{ $doctors->count() }})</h4>
            <div class="mc-table-wrap">
                <table class="mc-table">
                    <thead><tr><th>Name</th><th>Specialty</th><th>Experience</th><th>Fee (TZS)</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse ($doctors as $doctor)
                            <tr>
                                <td>{{ $doctor->user->name }}</td>
                                <td>{{ $doctor->specialty->name }}</td>
                                <td>{{ $doctor->experience_years }} yrs</td>
                                <td>{{ number_format($doctor->consultation_fee) }}</td>
                                <td><span class="mc-badge {{ $doctor->is_active ? 'mc-badge-confirmed' : 'mc-badge-cancelled' }}">{{ $doctor->is_active ? 'Active' : 'Inactive' }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="mc-empty">No doctors yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <h4 class="mc-subtitle">Appointments</h4>
            @include('partials.appointments-table', ['appointments' => $appointments, 'actor' => 'staff'])
        @else
            <p class="mc-empty text-center">No hospital is assigned to your account yet.</p>
        @endif
    </div>
</section>
@endsection
