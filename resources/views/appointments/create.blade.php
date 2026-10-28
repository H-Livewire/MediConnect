@extends('layouts.app')
@section('title', 'Book Appointment')

@section('content')
@include('partials.breadcrumbs', ['title' => 'Book Appointment'])

<section class="appointment single-page">
    <div class="container">
        @include('partials.section-title', ['title' => 'Book Your Appointment', 'text' => 'Select a doctor, a date and an available time slot.'])
        <div class="row">
            <div class="col-lg-7 col-md-12 col-12">
                <form class="form" method="POST" action="{{ route('appointments.store') }}" id="booking-form">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12 col-12">
                            <div class="form-group">
                                <select name="doctor_id" id="doctor_id" required>
                                    <option value="">Select doctor</option>
                                    @foreach ($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" @selected(old('doctor_id', $selectedDoctorId) == $doctor->id)>
                                            {{ $doctor->user->name }} — {{ $doctor->specialty->name }} ({{ $doctor->hospital->name }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('doctor_id')<small class="mc-error">{{ $message }}</small>@enderror
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="form-group">
                                <input type="date" name="appointment_date" id="appointment_date" value="{{ old('appointment_date') }}" min="{{ today()->toDateString() }}" max="{{ today()->addDays(60)->toDateString() }}" required>
                                @error('appointment_date')<small class="mc-error">{{ $message }}</small>@enderror
                            </div>
                        </div>
                        <div class="col-lg-12 col-12">
                            <label class="mc-label">Available time slots</label>
                            <div id="slots" class="mc-slots" data-old="{{ old('appointment_time') }}"><span class="mc-muted">Choose a doctor and date to see available times.</span></div>
                            @error('appointment_time')<small class="mc-error">{{ $message }}</small>@enderror
                        </div>
                        <div class="col-lg-12 col-12">
                            <div class="form-group">
                                <textarea name="reason" placeholder="Reason for visit (optional)">{{ old('reason') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-5 col-md-4 col-12">
                            <div class="form-group"><div class="button"><button type="submit" class="btn">Book An Appointment</button></div></div>
                        </div>
                        <div class="col-lg-7 col-md-8 col-12"><p>( You will receive a booking reference immediately )</p></div>
                    </div>
                </form>
            </div>
            <div class="col-lg-5 col-md-12">
                <div class="appointment-image"><img src="{{ asset('mediplus/img/appointment-care.png') }}" alt=""></div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
(function ($) {
    var $slots = $('#slots');
    var slotsUrl = "{{ url('/doctors') }}";

    function loadSlots() {
        var doctor = $('#doctor_id').val();
        var date = $('#appointment_date').val();
        if (!doctor || !date) { return; }
        $slots.html('<span class="mc-muted">Loading…</span>');
        $.getJSON(slotsUrl + '/' + doctor + '/slots', { date: date }).done(function (res) {
            if (!res.slots.length) {
                $slots.html('<span class="mc-muted">No slots available on this date. Try another day.</span>');
                return;
            }
            var old = $slots.data('old');
            var html = '';
            $.each(res.slots, function (i, t) {
                html += '<label class="mc-slot"><input type="radio" name="appointment_time" value="' + t + '" required' + (old === t ? ' checked' : '') + '><span>' + t + '</span></label>';
            });
            $slots.html(html);
        }).fail(function () {
            $slots.html('<span class="mc-error">Could not load slots. Please try again.</span>');
        });
    }

    $('#doctor_id').on('change', loadSlots);
    $('#appointment_date').on('change', loadSlots);
    loadSlots();
})(jQuery);
</script>
@endpush
