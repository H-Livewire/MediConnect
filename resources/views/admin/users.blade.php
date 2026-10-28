@extends('layouts.admin')
@section('title', 'Users')

@section('content')
<div class="ad-crumbs">Home <span>/</span> Users</div>
<div class="ad-head"><div><h1>Users</h1><p>Patients, doctors and administrators</p></div></div>
<div class="ad-card">
    <div class="ad-card-head">
        <div class="ad-filters">
            <a href="{{ route('admin.users', array_filter(['q' => request('q')])) }}" class="{{ $role === '' ? 'is-active' : '' }}">All</a>
            @foreach (['patient' => 'Patients', 'doctor' => 'Doctors', 'hospital_admin' => 'Hospital admins', 'super_admin' => 'Super admins'] as $value => $label)
                <a href="{{ route('admin.users', array_filter(['role' => $value, 'q' => request('q')])) }}" class="{{ $role === $value ? 'is-active' : '' }}">{{ $label }}</a>
            @endforeach
        </div>
    </div>
    <div class="ad-table-wrap">
        <table class="ad-table">
            <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Role</th><th>Joined</th></tr></thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td><b>{{ $user->name }}</b></td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone ?: '—' }}</td>
                        <td><span class="ad-badge neutral">{{ $user->role->label() }}</span></td>
                        <td>{{ $user->created_at?->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="ad-empty">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $users->links('admin.partials.pagination') }}
</div>
@endsection
