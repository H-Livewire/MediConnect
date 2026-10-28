@extends('layouts.app')
@section('title', 'Doctor Dashboard')

@section('content')
@include('partials.breadcrumbs', ['title' => 'Doctor Dashboard'])

<section class="mc-dashboard section">
    <div class="container">
        @if ($doctor)
            <div class="mc-dash-head">
                <div><h3>{{ auth()->user()->name }}</h3><p class="mc-muted">{{ $doctor->specialty->name }} · {{ $doctor->hospital->name }}</p></div>
            </div>
            @include('partials.appointments-table', ['appointments' => $appointments, 'actor' => 'staff'])
        @else
            <p class="mc-empty text-center">Your doctor profile has not been set up yet. Please contact your hospital administrator.</p>
        @endif
    </div>
</section>
@endsection
