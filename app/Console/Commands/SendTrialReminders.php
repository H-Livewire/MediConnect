<?php

namespace App\Console\Commands;

use App\Models\Hospital;
use App\Services\HospitalNotifier;
use Illuminate\Console\Command;

class SendTrialReminders extends Command
{
    protected $signature = 'hospitals:trial-reminders';

    protected $description = 'Remind hospital admins that their free trial is ending (or has just ended)';

    public function handle(HospitalNotifier $notifier): int
    {
        $sent = 0;

        foreach (config('mediconnect.reminder_days') as $days) {
            Hospital::with('admin')
                ->whereDate('trial_ends_at', today()->addDays($days))
                ->get()
                ->reject(fn (Hospital $h) => $h->subscriptionActive())
                ->each(function (Hospital $hospital) use ($notifier, $days, &$sent): void {
                    $notifier->trialEnding($hospital, $days);
                    $sent++;
                });
        }

        Hospital::with('admin')
            ->whereDate('trial_ends_at', today()->subDay())
            ->get()
            ->reject(fn (Hospital $h) => $h->subscriptionActive())
            ->each(function (Hospital $hospital) use ($notifier, &$sent): void {
                $notifier->trialEnded($hospital);
                $sent++;
            });

        $this->info("Sent {$sent} reminder(s).");

        return self::SUCCESS;
    }
}
