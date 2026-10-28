@extends('layouts.admin')
@section('title', 'Appointments')

@section('content')
<div class="ad-crumbs">Home <span>/</span> Appointments</div>
<div class="ad-head"><div><h1>Appointments</h1><p>Requests and bookings for {{ auth()->user()->managedHospital->name }}</p></div></div>
<div class="ad-card">
    <div class="ad-card-head">
        <div class="ad-filters">
            <a href="{{ route('hospital.appointments') }}" class="{{ $status === '' ? 'is-active' : '' }}">All</a>
            @foreach (['pending', 'confirmed', 'completed', 'cancelled'] as $s)
                <a href="{{ route('hospital.appointments', ['status' => $s]) }}" class="{{ $status === $s ? 'is-active' : '' }}">{{ ucfirst($s) }}</a>
            @endforeach
        </div>
    </div>
    <div class="ad-table-wrap">
        <table class="ad-table">
            <thead><tr><th>Ref</th><th>Date</th><th>Patient</th><th>Doctor</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($appointments as $appointment)
                    <tr>
                        <td><b>{{ $appointment->reference }}</b></td>
                        <td>{{ $appointment->appointment_date->format('d M Y') }}<small>{{ $appointment->timeLabel() }}</small></td>
                        <td>{{ $appointment->patient->name }}<small>{{ $appointment->patient->phone }}</small></td>
                        <td>{{ $appointment->doctor->user->name }}<small>{{ $appointment->doctor->specialty->name }}</small></td>
                        <td><span class="ad-badge {{ $appointment->status->value }}">{{ $appointment->status->label() }}</span></td>
                        <td>
                            <div class="ad-row-actions">
                                @foreach ([['pending', 'confirmed', 'Confirm', ''], ['confirmed', 'completed', 'Complete', ''], ['pending', 'cancelled', 'Reject', 'ad-btn-danger'], ['confirmed', 'cancelled', 'Cancel', 'ad-btn-danger']] as [$from, $to, $label, $class])
                                    @if ($appointment->status->value === $from)
                                        <form method="POST" action="{{ route('appointments.status', $appointment) }}">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="{{ $to }}">
                                            <button type="submit" class="ad-btn ad-btn-sm {{ $class }}">{{ $label }}</button>
                                        </form>
                                    @endif
                                @endforeach
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="ad-empty">No appointments found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $appointments->links('admin.partials.pagination') }}
</div>
@endsection
