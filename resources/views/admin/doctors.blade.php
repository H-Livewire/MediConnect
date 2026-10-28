@extends('layouts.admin')
@section('title', 'Doctors')

@section('content')
<div class="ad-crumbs">Home <span>/</span> Doctors</div>
<div class="ad-head"><div><h1>Doctors</h1><p>All registered doctors</p></div></div>
<div class="ad-card">
    <div class="ad-card-head">
        <form method="GET" class="ad-search" style="max-width:360px;width:100%"><i class="fa fa-search"></i><input type="search" name="q" value="{{ request('q') }}" placeholder="Search doctor name…"></form>
    </div>
    <div class="ad-table-wrap">
        <table class="ad-table">
            <thead><tr><th>Doctor</th><th>Specialty</th><th>Hospital</th><th>Experience</th><th>Fee (TZS)</th><th>Status</th></tr></thead>
            <tbody>
                @forelse ($doctors as $doctor)
                    <tr>
                        <td><a href="{{ route('doctors.show', $doctor) }}"><b>{{ $doctor->user->name }}</b></a><small>{{ $doctor->user->email }}</small></td>
                        <td>{{ $doctor->specialty->name }}</td>
                        <td>{{ $doctor->hospital->name }}</td>
                        <td>{{ $doctor->experience_years }} yrs</td>
                        <td>{{ number_format($doctor->consultation_fee) }}</td>
                        <td><span class="ad-badge {{ $doctor->is_active ? 'completed' : 'cancelled' }}">{{ $doctor->is_active ? 'Active' : 'Inactive' }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="ad-empty">No doctors found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $doctors->links('admin.partials.pagination') }}
</div>
@endsection
