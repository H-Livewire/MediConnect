<?php

namespace App\Http\Controllers\Hospital;

use App\Enums\AppointmentStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $hospital = $request->user()->managedHospital;
        $appointments = $hospital->appointments();

        return view('hospital.dashboard', [
            'hospital' => $hospital,
            'stats' => [
                'doctors' => $hospital->doctors()->count(),
                'today' => (clone $appointments)->whereDate('appointment_date', today())->count(),
                'pending' => (clone $appointments)->where('status', AppointmentStatus::Pending->value)->count(),
                'total' => (clone $appointments)->count(),
            ],
            'recent' => $hospital->appointments()
                ->with(['patient', 'doctor.user', 'doctor.specialty', 'hospital'])
                ->latest('appointment_date')->latest('appointment_time')->take(8)->get(),
            'checklist' => $hospital->setupChecklist(),
        ]);
    }
}
