<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Hospital admins whose trial/subscription ended (or who are suspended)
 * can only reach the subscription page and their profile.
 */
class EnsureHospitalAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $hospital = $request->user()?->managedHospital;

        abort_unless($hospital, 403, 'No hospital is linked to this account.');

        if (! $hospital->hasAccess()) {
            return redirect()->route('hospital.subscription')
                ->with('warning', $hospital->blockedReason() ?? 'Your access has ended. Please subscribe to continue.');
        }

        return $next($request);
    }
}
