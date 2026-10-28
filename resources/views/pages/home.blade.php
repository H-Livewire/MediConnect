@extends('layouts.app')
@section('title', 'Book Doctors & Hospitals Online')

@section('content')
<section class="slider">
    <div class="hero-slider">
        @foreach ([
            ['slider2.jpg', 'Find the <span>Right Doctor</span> Near <span>You!</span>', 'Search trusted specialists across Tanzania and book your appointment online in minutes.', 'doctors.index', 'Find a Doctor'],
            ['slider.jpg', 'Quality <span>Healthcare</span> You Can <span>Trust!</span>', 'Browse hospitals, compare specialties and choose the care that fits you and your family.', 'hospitals.index', 'View Hospitals'],
            ['slider3.jpg', 'Skip the Queue, <span>Book</span> <span>Online!</span>', 'Pick a date, pick a time slot, and get your confirmation instantly.', 'appointments.create', 'Get Appointment'],
        ] as [$img, $heading, $text, $route, $label])
            <div class="single-slider" style="background-image:url('{{ asset('mediplus/img/'.$img) }}')">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="text">
                                <h1>{!! $heading !!}</h1>
                                <p>{{ $text }}</p>
                                <div class="button">
                                    <a href="{{ route($route) }}" class="btn">{{ $label }}</a>
                                    <a href="{{ route('about') }}" class="btn primary">Learn More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>

<section class="schedule">
    <div class="container">
        <div class="schedule-inner">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="single-schedule first">
                        <div class="inner">
                            <div class="icon"><i class="fa fa-ambulance"></i></div>
                            <div class="single-content">
                                <span>Urgent care</span>
                                <h4>Emergency Cases</h4>
                                <p>For life-threatening emergencies go straight to the nearest hospital emergency department.</p>
                                <a href="{{ route('hospitals.index') }}">FIND A HOSPITAL<i class="fa fa-long-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="single-schedule middle">
                        <div class="inner">
                            <div class="icon"><i class="icofont-prescription"></i></div>
                            <div class="single-content">
                                <span>Browse specialists</span>
                                <h4>Doctors Timetable</h4>
                                <p>See each doctor's profile, hospital and weekly availability before you book.</p>
                                <a href="{{ route('doctors.index') }}">VIEW DOCTORS<i class="fa fa-long-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-12">
                    <div class="single-schedule last">
                        <div class="inner">
                            <div class="icon"><i class="icofont-ui-clock"></i></div>
                            <div class="single-content">
                                <span>Clinic hours</span>
                                <h4>Opening Hours</h4>
                                <ul class="time-sidual">
                                    <li class="day">Monday - Friday <span>8.00-17.00</span></li>
                                    <li class="day">Saturday <span>9.00-13.00</span></li>
                                    <li class="day">Sunday <span>Emergency</span></li>
                                </ul>
                                <a href="{{ route('contact') }}">CONTACT US<i class="fa fa-long-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="Feautes section">
    <div class="container">
        @include('partials.section-title', ['title' => 'Healthcare Made Simple For You & Your Family', 'text' => 'Three easy steps from searching to sitting in the doctor\'s room.'])
        <div class="row">
            @foreach ([
                ['icofont-search-user', 'Find a Doctor', 'Search by name, specialty or hospital and compare your options.'],
                ['icofont-calendar', 'Pick a Time', 'See live availability and choose a date and slot that suits you.'],
                ['icofont-doctor-alt', 'Get Confirmed', 'Receive an instant booking reference and manage it from your dashboard.'],
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

<div id="fun-facts" class="fun-facts section overlay">
    <div class="container">
        <div class="row">
            @foreach ([
                ['icofont-doctor', $stats['doctors'], 'Doctors'],
                ['icofont-hospital', $stats['hospitals'], 'Hospitals'],
                ['icofont-stethoscope-alt', $stats['specialties'], 'Specialties'],
                ['icofont-calendar', $stats['appointments'], 'Appointments Booked'],
            ] as [$icon, $count, $label])
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="single-fun">
                        <i class="icofont {{ $icon }}"></i>
                        <div class="content">
                            <span class="counter">{{ $count }}</span>
                            <p>{{ $label }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@if ($hospitals->isNotEmpty())
<section class="portfolio section">
    <div class="container">
        @include('partials.section-title', ['title' => 'Our Partner Hospitals', 'text' => 'Verified hospitals and clinics you can book with today.'])
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="owl-carousel portfolio-slider">
                    @foreach ($hospitals as $hospital)
                        <div class="single-pf">
                            <img src="{{ $hospital->imageUrl() }}" alt="{{ $hospital->name }}">
                            <a href="{{ route('hospitals.show', $hospital) }}" class="btn">{{ $hospital->name }}</a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<section class="services section">
    <div class="container">
        @include('partials.section-title', ['title' => 'Medical Specialties To Improve Your Health', 'text' => 'Choose a specialty and see the doctors available for it.'])
        <div class="row">
            @foreach ($specialties as $specialty)
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="single-service">
                        <i class="icofont {{ $specialty->icon }}"></i>
                        <h4><a href="{{ route('doctors.index', ['specialty' => $specialty->id]) }}">{{ $specialty->name }}</a></h4>
                        <p>{{ $specialty->description }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="team section">
    <div class="container">
        @include('partials.section-title', ['title' => 'Meet Our Doctors', 'text' => 'Experienced specialists ready to see you.'])
        <div class="row">
            @foreach ($doctors as $doctor)
                @include('partials.doctor-card', ['doctor' => $doctor])
            @endforeach
        </div>
        <div class="row">
            <div class="col-12 text-center">
                <div class="button mc-view-all"><a href="{{ route('doctors.index') }}" class="btn mc-btn-inline">View All Doctors</a></div>
            </div>
        </div>
    </div>
</section>

<section class="appointment">
    <div class="container">
        @include('partials.section-title', ['title' => 'We Are Always Ready to Help You. Book An Appointment', 'text' => 'Choose your doctor to continue to the booking page.'])
        <div class="row">
            <div class="col-lg-6 col-md-12 col-12">
                <form class="form" method="GET" action="{{ route('appointments.create') }}">
                    <div class="row">
                        <div class="col-lg-12 col-12">
                            <div class="form-group">
                                <select name="doctor" required>
                                    <option value="">Select a doctor</option>
                                    @foreach ($doctors as $doctor)
                                        <option value="{{ $doctor->id }}">{{ $doctor->user->name }} — {{ $doctor->specialty->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-5 col-md-4 col-12">
                            <div class="form-group">
                                <div class="button"><button type="submit" class="btn">Continue Booking</button></div>
                            </div>
                        </div>
                        <div class="col-lg-7 col-md-8 col-12">
                            <p>( You will confirm date and time on the next step )</p>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="appointment-image"><img src="{{ asset('mediplus/img/appointment-care.jpg') }}" alt=""></div>
            </div>
        </div>
    </div>
</section>
@endsection
