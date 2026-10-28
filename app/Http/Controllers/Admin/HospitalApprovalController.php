<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Services\HospitalNotifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class HospitalApprovalController extends Controller
{
    public function __invoke(Request $request, Hospital $hospital, string $action, HospitalNotifier $notifier): RedirectResponse
    {
        $hospital->loadMissing('admin');

        switch ($action) {
            case 'approve':
                $hospital->update(['verified_at' => now(), 'is_active' => true, 'suspended_reason' => null]);
                $notifier->approved($hospital);
                $message = $hospital->name.' was verified.';
                break;

            case 'suspend':
                $reason = $request->validate(['reason' => ['required', 'string', 'max:200']])['reason'];
                $hospital->update(['is_active' => false, 'suspended_reason' => $reason]);
                $notifier->suspended($hospital);
                $message = $hospital->name.' was suspended.';
                break;

            case 'reactivate':
                $hospital->update(['is_active' => true, 'suspended_reason' => null]);
                $notifier->reactivated($hospital);
                $message = $hospital->name.' was reactivated.';
                break;

            case 'extend':
                $days = (int) $request->validate(['days' => ['nullable', 'integer', 'between:1,365']])['days'] ?: (int) config('mediconnect.trial_days');
                $column = $hospital->subscriptionActive() ? 'subscription_ends_at' : 'trial_ends_at';
                $from = $hospital->{$column}?->isFuture() ? $hospital->{$column} : now();
                $hospital->update([$column => $from->copy()->addDays($days)]);
                $notifier->extended($hospital->fresh('admin'), $days);
                $message = "Added {$days} days to {$hospital->name}.";
                break;

            default:
                abort(404);
        }

        return back()->with('status', $message);
    }
}
