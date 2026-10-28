<?php

namespace App\Http\Controllers;

use App\Enums\AppointmentStatus;
use App\Enums\UserRole;
use App\Http\Requests\BookAppointmentRequest;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function create(Request $request): View
    {
        return view('appointments.create', [
            'doctors' => Doctor::active()->with(['user', 'specialty', 'hospital'])->get()->sortBy('user.name')->values(),
            'selectedDoctorId' => $request->integer('doctor') ?: null,
        ]);
    }

    public function store(BookAppointmentRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $doctor = Doctor::active()->with('schedules')->findOrFail($data['doctor_id']);
        $date = Carbon::parse($data['appointment_date']);

        $appointment = DB::transaction(function () use ($request, $doctor, $date, $data): Appointment {
            $doctor->newQuery()->whereKey($doctor->id)->lockForUpdate()->first();

            if (! in_array($data['appointment_time'], $doctor->availableSlots($date), true)) {
                throw ValidationException::withMessages([
                    'appointment_time' => 'That time slot is no longer available. Please choose another.',
                ]);
            }

            return Appointment::create([
                'patient_id' => $request->user()->id,
                'doctor_id' => $doctor->id,
                'hospital_id' => $doctor->hospital_id,
                'appointment_date' => $date,
                'appointment_time' => $data['appointment_time'],
                'reason' => $data['reason'] ?? null,
                'status' => AppointmentStatus::Pending,
            ]);
        });

        return redirect()->route('appointments.confirmation', $appointment);
    }

    public function confirmation(Request $request, Appointment $appointment): View
    {
        abort_unless($appointment->patient_id === $request->user()->id, 403);

        return view('appointments.confirmation', [
            'appointment' => $appointment->load(['doctor.user', 'doctor.specialty', 'hospital']),
        ]);
    }

    public function cancel(Request $request, Appointment $appointment): RedirectResponse
    {
        abort_unless($appointment->patient_id === $request->user()->id, 403);
        abort_unless($appointment->isCancellable(), 422);

        $appointment->update(['status' => AppointmentStatus::Cancelled]);

        return back()->with('status', 'Appointment '.$appointment->reference.' was cancelled.');
    }

    public function updateStatus(Request $request, Appointment $appointment): RedirectResponse
    {
        $user = $request->user();

        $allowed = match ($user->role) {
            UserRole::Doctor => $appointment->doctor_id === $user->doctor?->id,
            UserRole::HospitalAdmin => $appointment->hospital_id === $user->managedHospital?->id,
            default => true,
        };

        abort_unless($allowed, 403);

        $data = $request->validate([
            'status' => ['required', Rule::in(['confirmed', 'completed', 'cancelled'])],
        ]);

        $appointment->update(['status' => $data['status']]);

        return back()->with('status', 'Appointment '.$appointment->reference.' marked as '.$data['status'].'.');
    }
}
