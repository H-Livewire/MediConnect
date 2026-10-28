<div class="col-lg-3 col-md-6 col-12">
    <div class="single-team">
        <div class="t-head">
            <img src="{{ $doctor->photoUrl() }}" alt="{{ $doctor->user->name }}">
            <div class="t-icon">
                <a href="{{ route('appointments.create', ['doctor' => $doctor->id]) }}" class="btn">Book Appointment</a>
            </div>
        </div>
        <div class="t-bottom">
            <p>{{ $doctor->specialty->name }}</p>
            <h2><a href="{{ route('doctors.show', $doctor) }}">{{ $doctor->user->name }}</a></h2>
            <p class="mc-muted">{{ $doctor->hospital->name }}</p>
        </div>
    </div>
</div>
