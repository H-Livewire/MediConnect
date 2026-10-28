@extends('layouts.app')
@section('title', 'Appointment Confirmation')

@section('content')
@include('partials.breadcrumbs', ['title' => 'Appointment Confirmation'])

<section class="mail-seccess section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3 col-12">
                <div class="success-inner mc-confirm">
                    <h1><i class="fa fa-check"></i><span>Appointment Booked!</span></h1>
                    <p>Your reference number is <strong>{{ $appointment->reference }}</strong>. The status is <strong>{{ $appointment->status->label() }}</strong> until the doctor confirms.</p>
                    <ul class="mc-facts">
                        <li><i class="icofont-doctor"></i>{{ $appointment->doctor->user->name }} — {{ $appointment->doctor->specialty->name }}</li>
                        <li><i class="icofont-hospital"></i>{{ $appointment->hospital->name }}</li>
                        <li><i class="icofont-calendar"></i>{{ $appointment->appointment_date->format('l, d F Y') }} at {{ $appointment->timeLabel() }}</li>
                    </ul>
                    <a href="{{ route('dashboard') }}" class="btn">Go To Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
