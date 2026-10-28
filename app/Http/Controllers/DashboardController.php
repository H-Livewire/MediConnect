<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        $with = ['patient', 'doctor.user', 'doctor.specialty', 'hospital'];

        return match ($user->role) {
            UserRole::Patient => view('dashboard.patient', [
                'appointments' => $user->appointments()->with($with)->latest('appointment_date')->latest('appointment_time')->paginate(10),
            ]),
            UserRole::Doctor => view('dashboard.doctor', [
                'doctor' => $user->doctor?->load(['specialty', 'hospital', 'schedules']),
                'appointments' => Appointment::with($with)
                    ->where('doctor_id', $user->doctor?->id)
                    ->orderBy('appointment_date')->orderBy('appointment_time')->paginate(10),
            ]),
            UserRole::HospitalAdmin => $this->hospitalDashboard($user, $with),
            UserRole::SuperAdmin => redirect()->route('admin.dashboard'),
        };
    }

    /**
     * @param  list<string>  $with
     */
    private function hospitalDashboard(User $user, array $with): View
    {
        $hospital = $user->managedHospital;

        return view('dashboard.hospital', [
            'hospital' => $hospital,
            'doctors' => $hospital?->doctors()->with(['user', 'specialty'])->get() ?? collect(),
            'appointments' => Appointment::with($with)
                ->where('hospital_id', $hospital?->id)
                ->orderBy('appointment_date')->orderBy('appointment_time')->paginate(10),
        ]);
    }
}
