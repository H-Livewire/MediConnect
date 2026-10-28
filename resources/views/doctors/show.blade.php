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
                    @include('partials.stars', ['rating' => $doctor->averageRating(), 'count' => $doctor->ratingsTotal()])
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

        <div class="row mc-reviews">
            <div class="col-12">
                <h4 class="mc-subtitle">Doctor Rating</h4>
                @if (session('status'))<div class="mc-alert mc-alert-success">{{ session('status') }}</div>@endif

                @if ($canRate)
                    <form method="POST" action="{{ route('doctors.rate', $doctor) }}" class="mc-rate-form">
                        @csrf
                        <p class="mc-muted">{{ $myRating ? 'Update your rating' : 'How was your visit?' }}</p>
                        <div class="mc-star-input" role="radiogroup" aria-label="Rating from 1 to 5">
                            @for ($i = 5; $i >= 1; $i--)
                                <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" @checked(old('rating', $myRating?->rating) == $i) required>
                                <label for="star{{ $i }}" title="{{ $i }} star{{ $i > 1 ? 's' : '' }}"><i class="fa fa-star"></i></label>
                            @endfor
                        </div>
                        @error('rating')<small class="mc-error">{{ $message }}</small>@enderror
                        <textarea name="comment" rows="3" maxlength="500" placeholder="Share your experience (optional)">{{ old('comment', $myRating?->comment) }}</textarea>
                        <button type="submit" class="btn mc-btn-inline">Submit rating</button>
                    </form>
                @else
                    <p class="mc-muted">Patients can rate a doctor after a completed appointment.</p>
                @endif

                @foreach ($reviews as $review)
                    <div class="mc-review">
                        <strong>{{ $review->user->name }}</strong>
                        @include('partials.stars', ['rating' => $review->rating, 'count' => 0])
                        @if ($review->comment)<p>{{ $review->comment }}</p>@endif
                        <small class="mc-muted">{{ $review->created_at->diffForHumans() }}</small>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection
