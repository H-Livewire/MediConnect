@extends('layouts.admin')
@section('title', 'Hospitals')

@section('content')
<div class="ad-crumbs">Home <span>/</span> Hospitals</div>
<div class="ad-head"><div><h1>Hospitals</h1><p>Verify, suspend and manage access for registered hospitals</p></div></div>
<div class="ad-card">
    <div class="ad-card-head">
        <form method="GET" class="ad-search" style="max-width:360px;width:100%"><i class="fa fa-search"></i><input type="search" name="q" value="{{ request('q') }}" placeholder="Search name or city…"></form>
    </div>
    <div class="ad-table-wrap">
        <table class="ad-table">
            <thead><tr><th>Hospital</th><th>City</th><th>Admin</th><th>Doctors</th><th>Access</th><th></th></tr></thead>
            <tbody>
                @forelse ($hospitals as $hospital)
                    @php $state = $hospital->state(); @endphp
                    <tr>
                        <td><a href="{{ route('hospitals.show', $hospital) }}"><b>{{ $hospital->name }}</b></a><small>{{ $hospital->registration_number ?: $hospital->phone }}</small></td>
                        <td>{{ $hospital->city }}</td>
                        <td>{{ $hospital->admin?->name ?? '—' }}<small>{{ $hospital->admin?->email }}</small></td>
                        <td>{{ $hospital->doctors_count }}</td>
                        <td>
                            <span class="ad-badge {{ $state === 'pending' ? 'pending-review' : $state }}">{{ $hospital->stateLabel() }}</span>
                            @if ($hospital->blockedReason() && $state !== 'pending')<small>{{ $hospital->blockedReason() }}</small>@endif
                        </td>
                        <td>
                            <div class="ad-row-actions">
                                @unless ($hospital->isVerified())
                                    <form method="POST" action="{{ route('admin.hospitals.action', [$hospital, 'approve']) }}">@csrf @method('PATCH')<button class="ad-btn ad-btn-sm ad-btn-primary" type="submit">Approve</button></form>
                                @endunless

                                <details class="ad-pop">
                                    <summary class="ad-btn ad-btn-sm">+ Days</summary>
                                    <form class="ad-pop-box" method="POST" action="{{ route('admin.hospitals.action', [$hospital, 'extend']) }}">
                                        @csrf @method('PATCH')
                                        <input type="number" name="days" min="1" max="365" value="{{ config('mediconnect.subscription_days') }}" aria-label="Days to add">
                                        <button class="ad-btn ad-btn-sm ad-btn-primary" type="submit">Extend access</button>
                                    </form>
                                </details>

                                @if ($hospital->is_active)
                                    <details class="ad-pop">
                                        <summary class="ad-btn ad-btn-sm ad-btn-danger">Suspend</summary>
                                        <form class="ad-pop-box" method="POST" action="{{ route('admin.hospitals.action', [$hospital, 'suspend']) }}">
                                            @csrf @method('PATCH')
                                            <input type="text" name="reason" placeholder="Reason (shown to the hospital)" maxlength="200" required>
                                            <button class="ad-btn ad-btn-sm ad-btn-danger" type="submit">Suspend hospital</button>
                                        </form>
                                    </details>
                                @else
                                    <form method="POST" action="{{ route('admin.hospitals.action', [$hospital, 'reactivate']) }}">@csrf @method('PATCH')<button class="ad-btn ad-btn-sm" type="submit">Reactivate</button></form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="ad-empty">No hospitals found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $hospitals->links('admin.partials.pagination') }}
</div>
@endsection
