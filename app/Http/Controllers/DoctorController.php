<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Specialty;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DoctorController extends Controller
{
    public function index(Request $request): View
    {
        $doctors = Doctor::active()
            ->with(['user', 'specialty', 'hospital'])
            ->when($request->integer('specialty'), fn ($q, $id) => $q->where('specialty_id', $id))
            ->when($request->integer('hospital'), fn ($q, $id) => $q->where('hospital_id', $id))
            ->when($request->string('q')->trim()->toString(), fn ($q, $term) => $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$term}%")))
            ->paginate(12)
            ->withQueryString();

        return view('doctors.index', [
            'doctors' => $doctors,
            'specialties' => Specialty::orderBy('name')->get(),
            'hospitals' => Hospital::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function show(Doctor $doctor): View
    {
        abort_unless($doctor->is_active, 404);

        return view('doctors.show', [
            'doctor' => $doctor->load(['user', 'specialty', 'hospital', 'schedules']),
        ]);
    }

    public function slots(Request $request, Doctor $doctor): JsonResponse
    {
        $request->validate(['date' => ['required', 'date_format:Y-m-d']]);

        $doctor->load('schedules');

        return response()->json([
            'slots' => $doctor->availableSlots(Carbon::parse($request->query('date'))),
        ]);
    }
}
