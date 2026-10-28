@extends('layouts.admin')
@section('title', 'Dashboard')

@php
    $compact = fn (int $v): string => $v >= 1000000 ? rtrim(rtrim(number_format($v / 1000000, 1), '0'), '.').'M' : ($v >= 1000 ? rtrim(rtrim(number_format($v / 1000, 1), '0'), '.').'K' : (string) $v);
    $cards = [
        ['patients', 'Total Patients', 'fa-users', 'blue', false],
        ['doctors', 'Total Doctors', 'fa-user-md', 'green', false],
        ['hospitals', 'Hospitals', 'fa-hospital-o', 'slate', false],
        ['today', "Today's Appointments", 'fa-calendar-check-o', 'blue', false],
        ['pending', 'Pending Requests', 'fa-clock-o', 'amber', false],
        ['revenue', 'Revenue (TZS)', 'fa-money', 'green', true],
    ];
    $statusSegments = [
        ['label' => 'Confirmed', 'value' => $statusCounts['confirmed'], 'color' => '#1a76d1'],
        ['label' => 'Pending', 'value' => $statusCounts['pending'], 'color' => '#e0952b'],
        ['label' => 'Completed', 'value' => $statusCounts['completed'], 'color' => '#2fa57a'],
        ['label' => 'Cancelled', 'value' => $statusCounts['cancelled'], 'color' => '#e13138'],
    ];
    $revMax = max(1, max($revenueSeries['values']));
@endphp

@section('content')
<div class="ad-crumbs">Home <span>/</span> Dashboard</div>
<div class="ad-head">
    <div>
        <h1>Dashboard</h1>
        <p>Platform overview and today's activity</p>
    </div>
    <a href="{{ route('admin.appointments.export') }}" class="ad-btn"><i class="fa fa-download"></i>Export</a>
</div>

<div class="ad-stats">
    @foreach ($cards as [$key, $label, $icon, $tone, $isMoney])
        <?php $stat = $stats[$key]; ?>
        <div class="ad-card ad-stat">
            <div class="ad-stat-top">
                <span class="ad-ico {{ $tone }}"><i class="fa {{ $icon }}"></i></span>
                @if ($stat['trend'])
                    <span class="ad-trend {{ $stat['trend']['direction'] }}"><i class="fa fa-long-arrow-{{ $stat['trend']['direction'] }}"></i> {{ $stat['trend']['percent'] }}%</span>
                @endif
            </div>
            <div class="ad-stat-value">{{ $isMoney ? $compact($stat['value']) : number_format($stat['value']) }}</div>
            <div class="ad-stat-label">{{ $label }}</div>
        </div>
    @endforeach
</div>

<div class="ad-row r-2-1">
    <div class="ad-card">
        <div class="ad-card-head">
            <h2>Appointment Activity</h2>
            <div class="ad-tabs" data-range-tabs>
                <button type="button" data-range="weekly">Weekly</button>
                <button type="button" data-range="monthly" class="is-active">Monthly</button>
                <button type="button" data-range="yearly">Yearly</button>
            </div>
        </div>
        <div class="ad-card-body">
            @foreach ($ranges as $range => $data)
                <div class="ad-pane" data-pane="{{ $range }}" @if ($range !== 'monthly') hidden @endif>
                    @include('admin.partials.line-chart', [
                        'title' => ucfirst($range).' appointment activity',
                        'labels' => $data['labels'],
                        'series' => [['values' => $data['booked']], ['values' => $data['completed']]],
                    ])
                </div>
            @endforeach
            <div class="ad-legend">
                <span><i style="background: var(--ad-primary)"></i>Booked</span>
                <span><i style="background: var(--ad-muted)"></i>Completed</span>
            </div>
        </div>
    </div>

    <div class="ad-card">
        <div class="ad-card-head"><h2>Appointment Statistics</h2></div>
        <div class="ad-card-body">@include('admin.partials.donut', ['segments' => $statusSegments])</div>
    </div>
</div>

<div class="ad-row r-2-1">
    <div class="ad-card">
        <div class="ad-card-head"><h2>Revenue Overview</h2><span class="ad-chip">Completed visits · TZS</span></div>
        <div class="ad-card-body">
            <div class="ad-bars">
                @foreach ($revenueSeries['values'] as $i => $value)
                    <div class="ad-bar" title="{{ $revenueSeries['labels'][$i] }}: TZS {{ number_format($value) }}">
                        <span class="ad-bar-val">{{ $value > 0 ? $compact($value) : '' }}</span>
                        <div class="ad-bar-fill" style="height: {{ max(2, round($value / $revMax * 100)) }}%"></div>
                        <span class="ad-bar-lab">{{ $revenueSeries['labels'][$i] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="ad-card">
        <div class="ad-card-head"><h2>Top Hospitals</h2><a href="{{ route('admin.hospitals') }}" class="ad-trend up">View all</a></div>
        <div class="ad-card-body">
            <ul class="ad-list">
                @forelse ($topHospitals as $i => $hospital)
                    <li>
                        <span class="ad-rank">{{ $i + 1 }}</span>
                        <div><b>{{ $hospital->name }}</b><small>{{ $hospital->city }} · {{ $hospital->doctors_count }} doctors</small></div>
                        <span class="ad-end">{{ $hospital->appointments_count }}<small style="display:block;font-weight:400">visits</small></span>
                    </li>
                @empty
                    <li class="ad-empty">No hospitals yet.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

<div class="ad-card">
    <div class="ad-card-head"><h2>Recent Appointments</h2><a href="{{ route('admin.appointments') }}" class="ad-trend up">View all</a></div>
    <div class="ad-table-wrap">
        <table class="ad-table">
            <thead><tr><th>Ref</th><th>Date</th><th>Patient</th><th>Doctor</th><th>Hospital</th><th>Status</th></tr></thead>
            <tbody>@include('admin.partials.appointment-rows', ['appointments' => $recent])</tbody>
        </table>
    </div>
</div>
@endsection
