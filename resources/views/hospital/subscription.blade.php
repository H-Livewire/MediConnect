@extends('layouts.admin')
@section('title', 'Subscription')

@section('content')
<div class="ad-crumbs">Home <span>/</span> Subscription</div>
<div class="ad-head"><div><h1>Subscription</h1><p>Your plan and access to MediConnect</p></div></div>

<div class="ad-row r-2-1">
    <div class="ad-card">
        <div class="ad-card-head"><h2>Status</h2><span class="ad-badge {{ $hospital->state() === 'pending' ? 'pending-review' : $hospital->state() }}">{{ $hospital->stateLabel() }}</span></div>
        <div class="ad-card-body">
            <ul class="ad-checklist">
                <li><span>Verification</span><b style="margin-left:auto">{{ $hospital->isVerified() ? 'Verified '.$hospital->verified_at->format('d M Y') : 'Waiting for review' }}</b></li>
                <li><span>Free trial ends</span><b style="margin-left:auto">{{ $hospital->trial_ends_at?->format('d M Y') ?? '—' }}</b></li>
                <li><span>Subscription ends</span><b style="margin-left:auto">{{ $hospital->subscription_ends_at?->format('d M Y') ?? 'Not subscribed' }}</b></li>
                <li><span>Visible to patients</span><b style="margin-left:auto">{{ $hospital->isVisible() ? 'Yes' : 'No' }}</b></li>
            </ul>
            @if ($hospital->blockedReason())
                <div class="ad-banner is-warn" style="margin:16px 0 0"><i class="fa fa-info-circle"></i><span>{{ $hospital->blockedReason() }}</span></div>
            @endif
        </div>
    </div>

    <div class="ad-card ad-plan">
        <div class="ad-price">{{ $currency }} {{ number_format($price) }} <small>/ {{ $period }} days</small></div>
        <ul>
            <li><i class="fa fa-check"></i>Unlimited doctors</li>
            <li><i class="fa fa-check"></i>Appointments &amp; approvals</li>
            <li><i class="fa fa-check"></i>Doctor dashboards</li>
            <li><i class="fa fa-check"></i>Visible to all patients</li>
        </ul>
        <div><button type="button" class="ad-btn ad-btn-primary" disabled title="Online payment is coming soon">Pay with mobile money</button></div>
        <p class="ad-notif-empty">Online payment is coming soon. Contact MediConnect to activate your subscription.</p>
    </div>
</div>
@endsection
