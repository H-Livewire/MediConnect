<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\Hospital;
use App\Models\User;
use App\Notifications\SystemNotice;
use Throwable;

/**
 * Wording + delivery for every hospital lifecycle notification.
 * Failures (for example a broken mail server) never break the request.
 */
class HospitalNotifier
{
    public function welcome(Hospital $hospital): void
    {
        $days = config('mediconnect.trial_days');

        $this->toAdmin($hospital, new SystemNotice(
            'Welcome to MediConnect!',
            "{$hospital->name} has joined MediConnect and you have {$days} days of free trial (until {$hospital->trial_ends_at->format('d M Y')}). Our team will verify your hospital shortly; meanwhile add your doctors and complete your profile.",
            route('hospital.dashboard'),
            'fa-hand-peace-o',
        ));
    }

    public function registered(Hospital $hospital): void
    {
        User::where('role', UserRole::SuperAdmin)->get()->each(fn (User $admin) => $this->send($admin, new SystemNotice(
            'New hospital awaiting review',
            "{$hospital->name} ({$hospital->city}) has registered and needs verification.",
            route('admin.hospitals'),
            'fa-hospital-o',
            mail: false,
        )));
    }

    public function approved(Hospital $hospital): void
    {
        $this->toAdmin($hospital, new SystemNotice(
            'Your hospital is verified',
            "{$hospital->name} is now visible to patients on MediConnect.",
            route('hospital.dashboard'),
            'fa-check-circle',
        ));
    }

    public function suspended(Hospital $hospital): void
    {
        $this->toAdmin($hospital, new SystemNotice(
            'Your hospital has been suspended',
            "{$hospital->name} is hidden from patients. Reason: ".($hospital->suspended_reason ?: 'not specified').'.',
            route('hospital.subscription'),
            'fa-ban',
        ));
    }

    public function reactivated(Hospital $hospital): void
    {
        $this->toAdmin($hospital, new SystemNotice(
            'Your hospital is active again',
            "{$hospital->name} has been reactivated.",
            route('hospital.dashboard'),
            'fa-check-circle',
        ));
    }

    public function extended(Hospital $hospital, int $days): void
    {
        $this->toAdmin($hospital, new SystemNotice(
            'Your access was extended',
            "{$days} extra days were added. Access now runs until {$hospital->accessEndsAt()->format('d M Y')}.",
            route('hospital.subscription'),
            'fa-calendar-plus-o',
        ));
    }

    public function trialEnding(Hospital $hospital, int $daysLeft): void
    {
        $this->toAdmin($hospital, new SystemNotice(
            $daysLeft === 1 ? 'Your trial ends tomorrow' : "Your trial ends in {$daysLeft} days",
            'Subscribe to keep your hospital visible and keep receiving appointments.',
            route('hospital.subscription'),
            'fa-hourglass-half',
        ));
    }

    public function trialEnded(Hospital $hospital): void
    {
        $this->toAdmin($hospital, new SystemNotice(
            'Your trial has ended',
            "{$hospital->name} is hidden from patients until you subscribe. Your data is safe.",
            route('hospital.subscription'),
            'fa-exclamation-triangle',
        ));
    }

    private function toAdmin(Hospital $hospital, SystemNotice $notice): void
    {
        if ($hospital->admin) {
            $this->send($hospital->admin, $notice);
        }
    }

    private function send(User $user, SystemNotice $notice): void
    {
        try {
            $user->notify($notice);
        } catch (Throwable $e) {
            report($e);
        }
    }
}
