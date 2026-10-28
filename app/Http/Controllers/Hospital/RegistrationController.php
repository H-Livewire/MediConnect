<?php

namespace App\Http\Controllers\Hospital;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Models\User;
use App\Services\HospitalNotifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function create(): View
    {
        return view('hospital.register');
    }

    public function store(Request $request, HospitalNotifier $notifier): RedirectResponse
    {
        $data = $request->validate([
            'admin_name' => ['required', 'string', 'max:255'],
            'admin_email' => ['required', 'email:rfc', 'max:255', 'unique:users,email'],
            'admin_phone' => ['required', 'string', 'max:30'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'hospital_name' => ['required', 'string', 'max:255', 'unique:hospitals,name'],
            'registration_number' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:255'],
            'hospital_phone' => ['required', 'string', 'max:30'],
            'hospital_email' => ['nullable', 'email:rfc', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'terms' => ['accepted'],
        ], [
            'hospital_name.unique' => 'A hospital with this name is already registered.',
            'terms.accepted' => 'Please accept the terms to continue.',
        ]);

        $hospital = DB::transaction(function () use ($data): Hospital {
            $admin = User::create([
                'name' => $data['admin_name'],
                'email' => $data['admin_email'],
                'phone' => $data['admin_phone'],
                'password' => $data['password'],
                'role' => UserRole::HospitalAdmin,
            ]);

            return Hospital::create([
                'admin_id' => $admin->id,
                'name' => $data['hospital_name'],
                'slug' => Hospital::uniqueSlug($data['hospital_name']),
                'registration_number' => $data['registration_number'],
                'city' => $data['city'],
                'address' => $data['address'],
                'phone' => $data['hospital_phone'],
                'email' => $data['hospital_email'] ?? null,
                'website' => $data['website'] ?? null,
                'description' => $data['description'] ?? null,
                'trial_ends_at' => now()->addDays(config('mediconnect.trial_days')),
            ]);
        });

        Auth::login($hospital->admin);
        $request->session()->regenerate();

        $notifier->welcome($hospital->load('admin'));
        $notifier->registered($hospital);

        return redirect()->route('hospital.dashboard')
            ->with('status', 'Welcome to MediConnect! Your '.config('mediconnect.trial_days').'-day free trial has started.');
    }
}
