@extends('layouts.app')
@section('title', 'Super Admin Dashboard')

@section('content')
@include('partials.breadcrumbs', ['title' => 'Super Admin Dashboard'])

<section class="mc-dashboard section">
    <div class="container">
        <div class="row">
            @foreach ($stats as $label => $value)
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="mc-stat"><span>{{ $value }}</span><p>{{ ucfirst($label) }}</p></div>
                </div>
            @endforeach
        </div>

        <h4 class="mc-subtitle">Hospitals</h4>
        <div class="mc-table-wrap">
            <table class="mc-table">
                <thead><tr><th>Name</th><th>City</th><th>Doctors</th><th>Status</th></tr></thead>
                <tbody>
                    @foreach ($hospitals as $hospital)
                        <tr>
                            <td><a href="{{ route('hospitals.show', $hospital) }}">{{ $hospital->name }}</a></td>
                            <td>{{ $hospital->city }}</td>
                            <td>{{ $hospital->doctors_count }}</td>
                            <td><span class="mc-badge {{ $hospital->is_active ? 'mc-badge-confirmed' : 'mc-badge-cancelled' }}">{{ $hospital->is_active ? 'Active' : 'Inactive' }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <h4 class="mc-subtitle">Latest appointments</h4>
        @include('partials.appointments-table', ['appointments' => $appointments, 'actor' => 'staff'])

        <h4 class="mc-subtitle">Latest contact messages</h4>
        <div class="mc-table-wrap">
            <table class="mc-table">
                <thead><tr><th>From</th><th>Subject</th><th>Message</th><th>Received</th></tr></thead>
                <tbody>
                    @forelse ($messages as $message)
                        <tr>
                            <td>{{ $message->name }}<br><small>{{ $message->email }}</small></td>
                            <td>{{ $message->subject }}</td>
                            <td>{{ Str::limit($message->message, 80) }}</td>
                            <td>{{ $message->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="mc-empty">No messages.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
