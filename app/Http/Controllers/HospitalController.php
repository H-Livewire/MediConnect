<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HospitalController extends Controller
{
    public function index(Request $request): View
    {
        $hospitals = Hospital::where('is_active', true)
            ->withCount('doctors')
            ->when($request->string('q')->trim()->toString(), fn ($q, $term) => $q->where(fn ($w) => $w->where('name', 'like', "%{$term}%")->orWhere('city', 'like', "%{$term}%")))
            ->orderBy('name')
            ->paginate(9)
            ->withQueryString();

        return view('hospitals.index', ['hospitals' => $hospitals]);
    }

    public function show(Hospital $hospital): View
    {
        abort_unless($hospital->is_active, 404);

        return view('hospitals.show', [
            'hospital' => $hospital->load(['doctors' => fn ($q) => $q->active()->with(['user', 'specialty'])]),
        ]);
    }
}
