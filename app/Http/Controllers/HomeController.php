<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Specialty;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.home', [
            'hospitals' => Hospital::where('is_active', true)->latest()->take(8)->get(),
            'specialties' => Specialty::orderBy('name')->take(6)->get(),
            'doctors' => Doctor::active()->withRatingStats()->with(['user', 'specialty', 'hospital'])->inRandomOrder()->take(4)->get(),
            'stats' => [
                'doctors' => Doctor::active()->count(),
                'hospitals' => Hospital::where('is_active', true)->count(),
                'specialties' => Specialty::count(),
                'appointments' => Appointment::count(),
            ],
        ]);
    }
}
