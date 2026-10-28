<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('hospital.profile', ['hospital' => $request->user()->managedHospital]);
    }

    public function update(Request $request): RedirectResponse
    {
        $hospital = $request->user()->managedHospital;

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('hospitals', 'name')->ignore($hospital->id)],
            'registration_number' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email:rfc', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'services' => ['nullable', 'string', 'max:500'],
            'insurances' => ['nullable', 'string', 'max:500'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data['services'] = $this->tags($data['services'] ?? null);
        $data['insurances'] = $this->tags($data['insurances'] ?? null);

        if ($request->hasFile('image')) {
            if ($hospital->image && str_contains($hospital->image, '/')) {
                Storage::disk('public')->delete($hospital->image);
            }
            $data['image'] = $request->file('image')->store('hospitals', 'public');
        } else {
            unset($data['image']);
        }

        $hospital->update($data);

        return back()->with('status', 'Hospital profile updated.');
    }

    /**
     * "Lab, X-ray, Dental" -> ['Lab', 'X-ray', 'Dental']
     *
     * @return list<string>|null
     */
    private function tags(?string $value): ?array
    {
        $items = collect(preg_split('/[,\n]+/', (string) $value))
            ->map(fn ($item) => trim((string) $item))
            ->filter()->unique()->take(20)->values()->all();

        return $items ?: null;
    }
}
