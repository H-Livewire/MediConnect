<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ContactMessage;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListingController extends Controller
{
    public function hospitals(Request $request): View
    {
        $term = $request->string('q')->trim()->toString();

        return view('admin.hospitals', [
            'hospitals' => Hospital::with('admin')->withCount('doctors')
                ->when($term, fn ($q) => $q->where(fn ($w) => $w->where('name', 'like', "%{$term}%")->orWhere('city', 'like', "%{$term}%")))
                ->orderByRaw('verified_at is null desc')->orderBy('name')->paginate(15)->withQueryString(),
        ]);
    }

    public function doctors(Request $request): View
    {
        $term = $request->string('q')->trim()->toString();

        return view('admin.doctors', [
            'doctors' => Doctor::with(['user', 'specialty', 'hospital'])
                ->when($term, fn ($q) => $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$term}%")))
                ->latest()->paginate(15)->withQueryString(),
        ]);
    }

    public function appointments(Request $request): View
    {
        $status = $request->string('status')->toString();

        return view('admin.appointments', [
            'appointments' => Appointment::with(['patient', 'doctor.user', 'doctor.specialty', 'hospital'])
                ->when(in_array($status, ['pending', 'confirmed', 'completed', 'cancelled'], true), fn ($q) => $q->where('status', $status))
                ->orderByDesc('appointment_date')->orderByDesc('appointment_time')->paginate(15)->withQueryString(),
            'status' => $status,
        ]);
    }

    public function users(Request $request): View
    {
        $term = $request->string('q')->trim()->toString();
        $role = $request->string('role')->toString();

        return view('admin.users', [
            'users' => User::query()
                ->when($term, fn ($q) => $q->where(fn ($w) => $w->where('name', 'like', "%{$term}%")->orWhere('email', 'like', "%{$term}%")))
                ->when(in_array($role, ['patient', 'doctor', 'hospital_admin', 'super_admin'], true), fn ($q) => $q->where('role', $role))
                ->latest()->paginate(15)->withQueryString(),
            'role' => $role,
        ]);
    }

    public function messages(): View
    {
        return view('admin.messages', ['messages' => ContactMessage::latest()->paginate(15)]);
    }

    public function exportAppointments(): StreamedResponse
    {
        return response()->streamDownload(function (): void {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Reference', 'Date', 'Time', 'Patient', 'Doctor', 'Hospital', 'Status']);

            Appointment::with(['patient', 'doctor.user', 'hospital'])->orderByDesc('appointment_date')->chunk(500, function ($chunk) use ($out): void {
                foreach ($chunk as $a) {
                    fputcsv($out, [$a->reference, $a->appointment_date->toDateString(), $a->timeLabel(), $a->patient->name, $a->doctor->user->name, $a->hospital->name, $a->status->value]);
                }
            });

            fclose($out);
        }, 'appointments-'.now()->format('Ymd').'.csv', ['Content-Type' => 'text/csv']);
    }
}
