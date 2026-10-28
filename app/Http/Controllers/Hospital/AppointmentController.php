<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $hospital = $request->user()->managedHospital;
        $status = $request->string('status')->toString();

        return view('hospital.appointments', [
            'status' => $status,
            'appointments' => $hospital->appointments()
                ->with(['patient', 'doctor.user', 'doctor.specialty', 'hospital'])
                ->when(in_array($status, ['pending', 'confirmed', 'completed', 'cancelled'], true), fn ($q) => $q->where('status', $status))
                ->orderByDesc('appointment_date')->orderByDesc('appointment_time')
                ->paginate(15)->withQueryString(),
        ]);
    }
}
