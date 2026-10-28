<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $monthStart = now()->startOfMonth();
        $lastMonthStart = now()->subMonthNoOverflow()->startOfMonth();

        $patients = User::where('role', UserRole::Patient->value);
        $patientsThisMonth = (clone $patients)->where('created_at', '>=', $monthStart)->count();
        $patientsLastMonth = (clone $patients)->whereBetween('created_at', [$lastMonthStart, $monthStart])->count();

        $revenueThisMonth = $this->revenueBetween($monthStart, now()->endOfMonth());
        $revenueLastMonth = $this->revenueBetween($lastMonthStart, $monthStart->copy()->subSecond());

        $statusCounts = Appointment::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.dashboard', [
            'stats' => [
                'patients' => ['value' => $patients->count(), 'trend' => $this->trend($patientsThisMonth, $patientsLastMonth)],
                'doctors' => ['value' => Doctor::count(), 'trend' => null],
                'hospitals' => ['value' => Hospital::count(), 'trend' => null],
                'today' => ['value' => Appointment::whereDate('appointment_date', today())->count(), 'trend' => null],
                'pending' => ['value' => Appointment::where('status', 'pending')->count(), 'trend' => null],
                'revenue' => ['value' => $this->revenueBetween(null, null), 'trend' => $this->trend($revenueThisMonth, $revenueLastMonth)],
            ],
            'ranges' => [
                'weekly' => $this->activitySeries('weekly'),
                'monthly' => $this->activitySeries('monthly'),
                'yearly' => $this->activitySeries('yearly'),
            ],
            'statusCounts' => [
                'confirmed' => (int) ($statusCounts['confirmed'] ?? 0),
                'pending' => (int) ($statusCounts['pending'] ?? 0),
                'completed' => (int) ($statusCounts['completed'] ?? 0),
                'cancelled' => (int) ($statusCounts['cancelled'] ?? 0),
            ],
            'revenueSeries' => $this->revenueSeries(),
            'topHospitals' => Hospital::withCount(['doctors', 'appointments'])->orderByDesc('appointments_count')->take(5)->get(),
            'recent' => Appointment::with(['patient', 'doctor.user', 'doctor.specialty', 'hospital'])->latest()->take(6)->get(),
        ]);
    }

    /**
     * @return array{direction: string, percent: float}|null
     */
    private function trend(int|float $current, int|float $previous): ?array
    {
        if ($previous <= 0) {
            return $current > 0 ? ['direction' => 'up', 'percent' => 100.0] : null;
        }

        $percent = round((($current - $previous) / $previous) * 100, 1);

        return ['direction' => $percent >= 0 ? 'up' : 'down', 'percent' => abs($percent)];
    }

    private function revenueBetween(?Carbon $from, ?Carbon $to): int
    {
        return (int) Appointment::query()
            ->join('doctors', 'doctors.id', '=', 'appointments.doctor_id')
            ->where('appointments.status', 'completed')
            ->when($from && $to, fn ($q) => $q->whereBetween('appointments.appointment_date', [$from->toDateString(), $to->toDateString()]))
            ->sum('doctors.consultation_fee');
    }

    /**
     * @return array{labels: list<string>, booked: list<int>, completed: list<int>}
     */
    private function activitySeries(string $range): array
    {
        [$buckets, $keyFormat, $labelFormat] = match ($range) {
            'weekly' => [collect(range(6, 0))->map(fn (int $i) => today()->subDays($i)), 'Y-m-d', 'D'],
            'yearly' => [collect(range(4, 0))->map(fn (int $i) => today()->startOfYear()->subYears($i)), 'Y', 'Y'],
            default => [collect(range(5, 0))->map(fn (int $i) => today()->startOfMonth()->subMonthsNoOverflow($i)), 'Y-m', 'M'],
        };

        $appointments = Appointment::query()
            ->whereDate('appointment_date', '>=', $buckets->first()->toDateString())
            ->get(['appointment_date', 'status']);

        $grouped = $appointments->groupBy(fn (Appointment $a) => $a->appointment_date->format($keyFormat));

        return [
            'labels' => $buckets->map(fn (Carbon $d) => $d->format($labelFormat))->all(),
            'booked' => $buckets->map(fn (Carbon $d) => $this->countWhere($grouped->get($d->format($keyFormat)), fn ($a) => $a->status->value !== 'cancelled'))->all(),
            'completed' => $buckets->map(fn (Carbon $d) => $this->countWhere($grouped->get($d->format($keyFormat)), fn ($a) => $a->status->value === 'completed'))->all(),
        ];
    }

    private function countWhere(?Collection $items, callable $callback): int
    {
        return $items ? $items->filter($callback)->count() : 0;
    }

    /**
     * @return array{labels: list<string>, values: list<int>}
     */
    private function revenueSeries(): array
    {
        $months = collect(range(5, 0))->map(fn (int $i) => today()->startOfMonth()->subMonthsNoOverflow($i));

        /** @var EloquentCollection<int, Appointment> $completed */
        $completed = Appointment::with('doctor:id,consultation_fee')
            ->where('status', 'completed')
            ->whereDate('appointment_date', '>=', $months->first()->toDateString())
            ->get(['id', 'doctor_id', 'appointment_date']);

        $byMonth = $completed->groupBy(fn (Appointment $a) => $a->appointment_date->format('Y-m'));

        return [
            'labels' => $months->map(fn (Carbon $m) => $m->format('M'))->all(),
            'values' => $months->map(fn (Carbon $m) => (int) ($byMonth->get($m->format('Y-m'))?->sum(fn (Appointment $a) => $a->doctor->consultation_fee) ?? 0))->all(),
        ];
    }
}
