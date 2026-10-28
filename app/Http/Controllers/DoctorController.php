<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Specialty;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DoctorController extends Controller
{
    public function index(Request $request): View
    {
        $doctors = Doctor::active()
            ->withRatingStats()
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

    public function show(Request $request, Doctor $doctor): View
    {
        abort_unless($doctor->is_active, 404);

        return view('doctors.show', [
            'doctor' => $doctor->loadAvg('ratings', 'rating')->loadCount('ratings')->load(['user', 'specialty', 'hospital', 'schedules']),
            'reviews' => $doctor->ratings()->with('user')->latest()->take(10)->get(),
            'myRating' => $request->user() ? $doctor->ratings()->where('user_id', $request->user()->id)->first() : null,
            'canRate' => $doctor->canBeRatedBy($request->user()),
        ]);
    }

    public function rate(Request $request, Doctor $doctor): RedirectResponse
    {
        abort_unless($doctor->canBeRatedBy($request->user()), 403, 'You can rate a doctor after a completed appointment.');

        $data = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:500'],
        ]);

        $doctor->ratings()->updateOrCreate(['user_id' => $request->user()->id], $data);

        return back()->with('status', 'Thank you for rating Dr. '.$doctor->user->name.'.');
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
