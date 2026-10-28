@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
@php
    $cards = [
        ['doctors', 'Doctors', 'fa-user-md', 'green'],
        ['today', "Today's Appointments", 'fa-calendar-check-o', 'blue'],
        ['pending', 'Pending Requests', 'fa-clock-o', 'amber'],
        ['total', 'All Appointments', 'fa-list-alt', 'slate'],
    ];
    $done = collect($checklist)->where('done', true)->count();
    $percent = (int) round($done / max(1, count($checklist)) * 100);
@endphp
<div class="ad-crumbs">Home <span>/</span> Dashboard</div>
<div class="ad-head">
    <div><h1>{{ $hospital->name }}</h1><p>{{ $hospital->city }} · {{ $hospital->stateLabel() }}</p></div>
    <a href="{{ route('hospital.doctors.create') }}" class="ad-btn ad-btn-primary"><i class="fa fa-plus"></i>Add doctor</a>
</div>

<div class="ad-stats is-4">
    @foreach ($cards as [$key, $label, $icon, $tone])
        <div class="ad-card ad-stat">
            <div class="ad-stat-top"><span class="ad-ico {{ $tone }}"><i class="fa {{ $icon }}"></i></span></div>
            <div class="ad-stat-value">{{ number_format($stats[$key]) }}</div>
            <div class="ad-stat-label">{{ $label }}</div>
        </div>
    @endforeach
</div>

<div class="ad-row r-2-1">
    <div class="ad-card">
        <div class="ad-card-head"><h2>Recent appointments</h2><a href="{{ route('hospital.appointments') }}" class="ad-link">View all</a></div>
        <div class="ad-table-wrap">
            <table class="ad-table">
                <thead><tr><th>Ref</th><th>Date</th><th>Patient</th><th>Doctor</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse ($recent as $appointment)
                        <tr>
                            <td><b>{{ $appointment->reference }}</b></td>
                            <td>{{ $appointment->appointment_date->format('d M Y') }}<small>{{ $appointment->timeLabel() }}</small></td>
                            <td>{{ $appointment->patient->name }}<small>{{ $appointment->patient->phone }}</small></td>
                            <td>{{ $appointment->doctor->user->name }}</td>
                            <td><span class="ad-badge {{ $appointment->status->value }}">{{ $appointment->status->label() }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="ad-empty">No appointments yet. They will appear here once patients book.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="ad-card">
        <div class="ad-card-head"><h2>Complete your setup</h2><span class="ad-badge {{ $percent === 100 ? 'completed' : 'pending' }}">{{ $percent }}%</span></div>
        <div class="ad-card-body">
            <div class="ad-progress"><i style="width: {{ $percent }}%"></i></div>
            <ul class="ad-checklist">
                @foreach ($checklist as $item)
                    <li class="{{ $item['done'] ? 'is-done' : '' }}">
                        <span class="ad-tick">@if ($item['done'])<i class="fa fa-check"></i>@endif</span>
                        <span>{{ $item['label'] }}</span>
                        @unless ($item['done'])<a href="{{ route($item['route']) }}">Do it</a>@endunless
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
