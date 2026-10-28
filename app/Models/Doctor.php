<?php

namespace App\Models;

use App\Enums\AppointmentStatus;
use App\Enums\UserRole;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Doctor extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id', 'hospital_id', 'specialty_id', 'bio', 'experience_years', 'consultation_fee', 'photo', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class);
    }

    public function specialty(): BelongsTo
    {
        return $this->belongsTo(Specialty::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(DoctorSchedule::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(DoctorRating::class);
    }

    public function scopeWithRatingStats(Builder $query): Builder
    {
        return $query->withAvg('ratings', 'rating')->withCount('ratings');
    }

    public function averageRating(): float
    {
        return round((float) ($this->ratings_avg_rating ?? $this->ratings()->avg('rating')), 1);
    }

    public function ratingsTotal(): int
    {
        return (int) ($this->ratings_count ?? $this->ratings()->count());
    }

    public function canBeRatedBy(?User $user): bool
    {
        return $user !== null
            && $user->role === UserRole::Patient
            && $this->appointments()
                ->where('patient_id', $user->id)
                ->where('status', AppointmentStatus::Completed->value)
                ->exists();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function photoUrl(): string
    {
        return asset('mediplus/img/'.($this->photo ?: 'team1.jpg'));
    }

    /**
     * Free time slots (H:i) for the given date.
     *
     * @return list<string>
     */
    public function availableSlots(CarbonInterface $date): array
    {
        if ($date->isBefore(today())) {
            return [];
        }

        $schedule = $this->schedules->firstWhere('weekday', $date->dayOfWeek);

        if (! $schedule) {
            return [];
        }

        $taken = $this->appointments()
            ->whereDate('appointment_date', $date)
            ->where('status', '!=', 'cancelled')
            ->pluck('appointment_time')
            ->map(fn (string $time): string => substr($time, 0, 5))
            ->all();

        $cursor = Carbon::parse($date->toDateString().' '.$schedule->start_time);
        $end = Carbon::parse($date->toDateString().' '.$schedule->end_time);
        $slots = [];

        while ($cursor->copy()->addMinutes($schedule->slot_minutes)->lessThanOrEqualTo($end)) {
            $label = $cursor->format('H:i');

            if (! in_array($label, $taken, true) && $cursor->isFuture()) {
                $slots[] = $label;
            }

            $cursor->addMinutes($schedule->slot_minutes);
        }

        return $slots;
    }
}
