@extends('layouts.app')
@section('title', 'About Us')

@section('content')
@include('partials.breadcrumbs', ['title' => 'About Us'])

<section class="about-area section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-12">
                <div class="about-image"><img src="{{ asset('mediplus/img/about-img.jpg') }}" alt="About MediConnect"></div>
            </div>
            <div class="col-lg-6 col-12">
                <div class="about-content">
                    <h2>Connecting Patients With Trusted Care</h2>
                    <p>MediConnect is a healthcare appointment platform that brings doctors and hospitals together with the people who need them. Patients can discover specialists, check real availability and book a visit without queueing.</p>
                    <p>Hospitals manage their doctors and bookings in one place, while doctors see their daily schedule and confirm visits with a single click.</p>
                    <div class="button"><a href="{{ route('doctors.index') }}" class="btn mc-btn-inline">Find a Doctor</a></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="Feautes section">
    <div class="container">
        @include('partials.section-title', ['title' => 'Why Choose MediConnect'])
        <div class="row">
            @foreach ([
                ['icofont-clock-time', 'Save Time', 'No phone tag and no waiting rooms — book in minutes.'],
                ['icofont-shield', 'Secure & Private', 'Your account and appointment details are protected.'],
                ['icofont-hospital', 'Trusted Network', 'Doctors and hospitals managed by verified administrators.'],
            ] as [$icon, $heading, $text])
                <div class="col-lg-4 col-12">
                    <div class="single-features">
                        <div class="signle-icon"><i class="icofont {{ $icon }}"></i></div>
                        <h3>{{ $heading }}</h3>
                        <p>{{ $text }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
