<?php

namespace App\Enums;

enum UserRole: string
{
    case Patient = 'patient';
    case Doctor = 'doctor';
    case HospitalAdmin = 'hospital_admin';
    case SuperAdmin = 'super_admin';

    public function label(): string
    {
        return match ($this) {
            self::Patient => 'Patient',
            self::Doctor => 'Doctor',
            self::HospitalAdmin => 'Hospital Admin',
            self::SuperAdmin => 'Super Admin',
        };
    }
}
