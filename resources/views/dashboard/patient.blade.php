@extends('layouts.app')
@section('title', 'Patient Dashboard')

@section('content')
@include('partials.breadcrumbs', ['title' => 'Patient Dashboard'])

<section class="mc-dashboard section">
    <div class="container">
        <div class="mc-dash-head">
            <div><h3>Welcome, {{ auth()->user()->name }}</h3><p class="mc-muted">Your appointments</p></div>
            <a href="{{ route('appointments.create') }}" class="btn mc-btn-inline">New Appointment</a>
        </div>
        @include('partials.appointments-table', ['appointments' => $appointments, 'actor' => 'patient'])
    </div>
</section>
@endsection
