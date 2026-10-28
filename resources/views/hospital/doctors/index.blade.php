@extends('layouts.admin')
@section('title', 'Doctors')

@section('content')
<div class="ad-crumbs">Home <span>/</span> Doctors</div>
<div class="ad-head">
    <div><h1>Doctors</h1><p>Manage the doctors of your hospital</p></div>
    <a href="{{ route('hospital.doctors.create') }}" class="ad-btn ad-btn-primary"><i class="fa fa-plus"></i>Add doctor</a>
</div>
<div class="ad-card">
    <div class="ad-card-head">
        <form method="GET" class="ad-search" style="max-width:360px;width:100%"><i class="fa fa-search"></i><input type="search" name="q" value="{{ request('q') }}" placeholder="Search doctor name…"></form>
    </div>
    <div class="ad-table-wrap">
        <table class="ad-table">
            <thead><tr><th>Doctor</th><th>Specialty</th><th>Experience</th><th>Fee (TZS)</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($doctors as $doctor)
                    <tr>
                        <td>
                            <div class="ad-person">
                                <img src="{{ $doctor->photoUrl() }}" alt="">
                                <span><b>{{ $doctor->user->name }}</b><small>{{ $doctor->user->email }}</small></span>
                            </div>
                        </td>
                        <td>{{ $doctor->specialty->name }}</td>
                        <td>{{ $doctor->experience_years }} yrs</td>
                        <td>{{ number_format($doctor->consultation_fee) }}</td>
                        <td>
                            <span class="ad-badge {{ $doctor->is_active ? 'completed' : 'cancelled' }}">{{ $doctor->is_active ? 'Active' : 'Inactive' }}</span>
                            @if ($doctor->isAway())<span class="ad-badge pending">Away today</span>@endif
                        </td>
                        <td>
                            <div class="ad-row-actions">
                                <a href="{{ route('hospital.doctors.edit', $doctor) }}" class="ad-btn ad-btn-sm">Edit</a>
                                <form method="POST" action="{{ route('hospital.doctors.away', $doctor) }}">@csrf @method('PATCH')<button type="submit" class="ad-btn ad-btn-sm">{{ $doctor->isAway() ? 'Back today' : 'Away today' }}</button></form>
                                <form method="POST" action="{{ route('hospital.doctors.toggle', $doctor) }}">@csrf @method('PATCH')<button type="submit" class="ad-btn ad-btn-sm {{ $doctor->is_active ? 'ad-btn-danger' : '' }}">{{ $doctor->is_active ? 'Deactivate' : 'Activate' }}</button></form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="ad-empty">You have not added any doctors yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $doctors->links('admin.partials.pagination') }}
</div>
@endsection
