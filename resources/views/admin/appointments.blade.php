@extends('layouts.admin')
@section('title', 'Appointments')

@section('content')
<div class="ad-crumbs">Home <span>/</span> Appointments</div>
<div class="ad-head">
    <div><h1>Appointments</h1><p>All bookings across hospitals</p></div>
    <a href="{{ route('admin.appointments.export') }}" class="ad-btn"><i class="fa fa-download"></i>Export</a>
</div>
<div class="ad-card">
    <div class="ad-card-head">
        <div class="ad-filters">
            <a href="{{ route('admin.appointments') }}" class="{{ $status === '' ? 'is-active' : '' }}">All</a>
            @foreach (['pending', 'confirmed', 'completed', 'cancelled'] as $s)
                <a href="{{ route('admin.appointments', ['status' => $s]) }}" class="{{ $status === $s ? 'is-active' : '' }}">{{ ucfirst($s) }}</a>
            @endforeach
        </div>
    </div>
    <div class="ad-table-wrap">
        <table class="ad-table">
            <thead><tr><th>Ref</th><th>Date</th><th>Patient</th><th>Doctor</th><th>Hospital</th><th>Status</th></tr></thead>
            <tbody>@include('admin.partials.appointment-rows', ['appointments' => $appointments])</tbody>
        </table>
    </div>
    {{ $appointments->links('admin.partials.pagination') }}
</div>
@endsection
