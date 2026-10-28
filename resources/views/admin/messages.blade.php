@extends('layouts.admin')
@section('title', 'Messages')

@section('content')
<div class="ad-crumbs">Home <span>/</span> Messages</div>
<div class="ad-head"><div><h1>Messages</h1><p>Contact form submissions</p></div></div>
<div class="ad-card">
    <div class="ad-table-wrap">
        <table class="ad-table">
            <thead><tr><th>From</th><th>Subject</th><th>Message</th><th>Received</th></tr></thead>
            <tbody>
                @forelse ($messages as $message)
                    <tr>
                        <td><b>{{ $message->name }}</b><small>{{ $message->email }}{{ $message->phone ? ' · '.$message->phone : '' }}</small></td>
                        <td>{{ $message->subject }}</td>
                        <td style="max-width:420px">{{ $message->message }}</td>
                        <td>{{ $message->created_at->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="ad-empty">No messages yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $messages->links('admin.partials.pagination') }}
</div>
@endsection
