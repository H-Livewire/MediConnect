<?php

namespace App\Http\Controllers\Hospital;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class DoctorController extends Controller
{
    public function index(Request $request): View
    {
        $term = $request->string('q')->trim()->toString();

        return view('hospital.doctors.index', [
            'doctors' => $this->hospital($request)->doctors()
                ->with(['user', 'specialty'])
                ->when($term, fn ($q) => $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$term}%")))
                ->latest()->paginate(12)->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('hospital.doctors.form', ['doctor' => null, 'specialties' => Specialty::orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $hospital = $this->hospital($request);
        $data = $request->validate($this->rules(null));

        DB::transaction(function () use ($request, $data, $hospital): void {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => $data['password'],
                'role' => UserRole::Doctor,
            ]);

            $doctor = $hospital->doctors()->create([
                'user_id' => $user->id,
                'specialty_id' => $this->specialtyId($data),
                'bio' => $data['bio'] ?? null,
                'experience_years' => $data['experience_years'],
                'consultation_fee' => $data['consultation_fee'],
                'photo' => $request->hasFile('photo') ? $request->file('photo')->store('doctors', 'public') : null,
            ]);

            // Sensible default week; the doctor can adjust it from their own dashboard.
            foreach (range(1, 5) as $weekday) {
                $doctor->schedules()->create(['weekday' => $weekday, 'start_time' => '08:00', 'end_time' => '16:00', 'slot_minutes' => 30]);
            }
        });

        return redirect()->route('hospital.doctors.index')->with('status', $data['name'].' was added to your hospital.');
    }

    public function edit(Request $request, Doctor $doctor): View
    {
        $this->authorizeDoctor($request, $doctor);

        return view('hospital.doctors.form', [
            'doctor' => $doctor->load(['user', 'specialty']),
            'specialties' => Specialty::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Doctor $doctor): RedirectResponse
    {
        $this->authorizeDoctor($request, $doctor);
        $data = $request->validate($this->rules($doctor));

        DB::transaction(function () use ($request, $data, $doctor): void {
            $doctor->user->fill([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
            ]);

            if (! empty($data['password'])) {
                $doctor->user->password = $data['password'];
            }

            $doctor->user->save();

            $attributes = [
                'specialty_id' => $this->specialtyId($data),
                'bio' => $data['bio'] ?? null,
                'experience_years' => $data['experience_years'],
                'consultation_fee' => $data['consultation_fee'],
            ];

            if ($request->hasFile('photo')) {
                if ($doctor->photo && str_contains($doctor->photo, '/')) {
                    Storage::disk('public')->delete($doctor->photo);
                }
                $attributes['photo'] = $request->file('photo')->store('doctors', 'public');
            }

            $doctor->update($attributes);
        });

        return redirect()->route('hospital.doctors.index')->with('status', 'Doctor details updated.');
    }

    public function toggle(Request $request, Doctor $doctor): RedirectResponse
    {
        $this->authorizeDoctor($request, $doctor);
        $doctor->update(['is_active' => ! $doctor->is_active]);

        return back()->with('status', $doctor->user->name.($doctor->is_active ? ' is now active.' : ' was deactivated and is hidden from patients.'));
    }

    /** "Away today" switch: no bookable slots until the end of today. */
    public function away(Request $request, Doctor $doctor): RedirectResponse
    {
        $this->authorizeDoctor($request, $doctor);
        $doctor->update(['away_until' => $doctor->isAway() ? null : today()]);

        return back()->with('status', $doctor->user->name.($doctor->isAway() ? ' is marked away today.' : ' is available again.'));
    }

    private function hospital(Request $request): Hospital
    {
        return $request->user()->managedHospital;
    }

    private function authorizeDoctor(Request $request, Doctor $doctor): void
    {
        abort_unless($doctor->hospital_id === $this->hospital($request)->id, 403);
    }

    /**
     * @return array<string, mixed>
     */
    private function rules(?Doctor $doctor): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc', 'max:255', Rule::unique('users', 'email')->ignore($doctor?->user_id)],
            'phone' => ['required', 'string', 'max:30'],
            'password' => [$doctor ? 'nullable' : 'required', Password::min(8)],
            'specialty_id' => ['nullable', 'required_without:new_specialty', 'exists:specialties,id'],
            'new_specialty' => ['nullable', 'required_without:specialty_id', 'string', 'max:100'],
            'experience_years' => ['required', 'integer', 'between:0,70'],
            'consultation_fee' => ['required', 'integer', 'min:0', 'max:10000000'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function specialtyId(array $data): int
    {
        if (! empty($data['new_specialty'])) {
            $name = trim($data['new_specialty']);

            return Specialty::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name])->id;
        }

        return (int) $data['specialty_id'];
    }
}
