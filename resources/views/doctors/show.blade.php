@extends('layouts.app')
@section('title', $doctor->user->name)

@section('content')
@include('partials.breadcrumbs', ['title' => 'Doctor Details'])

<section class="portfolio-single section">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-12">
                <div class="mc-doctor-photo"><img src="{{ $doctor->photoUrl() }}" alt="{{ $doctor->user->name }}"></div>
            </div>
            <div class="col-lg-7 col-12">
                <div class="mc-doctor-info">
                    <h2>{{ $doctor->user->name }}</h2>
                    <p class="mc-specialty">{{ $doctor->specialty->name }}</p>
                    <p>{{ $doctor->bio }}</p>
                    <ul class="mc-facts">
                        <li><i class="icofont-hospital"></i><a href="{{ route('hospitals.show', $doctor->hospital) }}">{{ $doctor->hospital->name }}</a>, {{ $doctor->hospital->city }}</li>
                        <li><i class="icofont-award"></i>{{ $doctor->experience_years }} years experience</li>
                        <li><i class="icofont-money"></i>Consultation fee: TZS {{ number_format($doctor->consultation_fee) }}</li>
                    </ul>
                    <h4 class="mc-subtitle">Weekly availability</h4>
                    <ul class="time-sidual mc-schedule">
                        @forelse ($doctor->schedules->sortBy('weekday') as $schedule)
                            <li class="day">{{ $schedule->weekdayName() }} <span>{{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}</span></li>
                        @empty
                            <li class="day">No schedule published yet.</li>
                        @endforelse
                    </ul>
                    <div class="button"><a href="{{ route('appointments.create', ['doctor' => $doctor->id]) }}" class="btn mc-btn-inline">Book Appointment</a></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
