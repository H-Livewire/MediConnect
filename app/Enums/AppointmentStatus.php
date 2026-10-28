<?php

namespace App\Enums;

enum AppointmentStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return ucfirst($this->value);
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Pending => 'mc-badge-pending',
            self::Confirmed => 'mc-badge-confirmed',
            self::Completed => 'mc-badge-completed',
            self::Cancelled => 'mc-badge-cancelled',
        };
    }
}
