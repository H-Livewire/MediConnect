<?php

namespace App\Models;

use App\Enums\AppointmentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Appointment extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'reference', 'patient_id', 'doctor_id', 'hospital_id', 'appointment_date', 'appointment_time', 'status', 'reason',
    ];

    protected function casts(): array
    {
        return [
            'appointment_date' => 'date',
            'status' => AppointmentStatus::class,
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Appointment $appointment): void {
            $appointment->reference ??= 'MC-'.strtoupper(Str::random(8));
        });
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class);
    }

    public function timeLabel(): string
    {
        return substr($this->appointment_time, 0, 5);
    }

    public function isCancellable(): bool
    {
        return in_array($this->status, [AppointmentStatus::Pending, AppointmentStatus::Confirmed], true)
            && ($this->appointment_date->isFuture() || $this->appointment_date->isToday());
    }
}
