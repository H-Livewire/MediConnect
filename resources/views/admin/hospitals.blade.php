@extends('layouts.admin')
@section('title', 'Hospitals')

@section('content')
<div class="ad-crumbs">Home <span>/</span> Hospitals</div>
<div class="ad-head"><div><h1>Hospitals</h1><p>Registered hospitals and clinics</p></div></div>
<div class="ad-card">
    <div class="ad-card-head">
        <form method="GET" class="ad-search" style="max-width:360px;width:100%"><i class="fa fa-search"></i><input type="search" name="q" value="{{ request('q') }}" placeholder="Search name or city…"></form>
    </div>
    <div class="ad-table-wrap">
        <table class="ad-table">
            <thead><tr><th>Hospital</th><th>City</th><th>Admin</th><th>Doctors</th><th>Status</th></tr></thead>
            <tbody>
                @forelse ($hospitals as $hospital)
                    <tr>
                        <td><a href="{{ route('hospitals.show', $hospital) }}"><b>{{ $hospital->name }}</b></a><small>{{ $hospital->phone }}</small></td>
                        <td>{{ $hospital->city }}</td>
                        <td>{{ $hospital->admin?->name ?? '—' }}<small>{{ $hospital->admin?->email }}</small></td>
                        <td>{{ $hospital->doctors_count }}</td>
                        <td><span class="ad-badge {{ $hospital->is_active ? 'completed' : 'cancelled' }}">{{ $hospital->is_active ? 'Active' : 'Inactive' }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="ad-empty">No hospitals found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $hospitals->links('admin.partials.pagination') }}
</div>
@endsection
