<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@mediconnect.test'], [
            'name' => 'MediConnect Admin', 'phone' => '+255700000001', 'password' => 'password', 'role' => UserRole::SuperAdmin,
        ]);

        User::updateOrCreate(['email' => 'patient@mediconnect.test'], [
            'name' => 'Asha Patient', 'phone' => '+255700000002', 'password' => 'password', 'role' => UserRole::Patient,
        ]);

        $specialties = collect([
            ['General Medicine', 'icofont-prescription', 'Primary care for everyday health concerns.'],
            ['Cardiology', 'icofont-heart-beat', 'Heart and blood-vessel care.'],
            ['Paediatrics', 'icofont-baby', 'Healthcare for babies, children and teens.'],
            ['Dentistry', 'icofont-tooth', 'Dental check-ups, fillings and oral care.'],
            ['Gynaecology', 'icofont-doctor', 'Women\'s reproductive and maternal health.'],
            ['Neurology', 'icofont-brain', 'Brain, spinal cord and nervous-system care.'],
        ])->map(fn (array $s) => Specialty::updateOrCreate(['slug' => Str::slug($s[0])], [
            'name' => $s[0], 'icon' => $s[1], 'description' => $s[2],
        ]));

        $hospitals = [
            ['Muhimbili National Hospital', 'Dar es Salaam', 'United Nations Road, Upanga', 'pf1.jpg', 'admin.muhimbili@mediconnect.test'],
            ['Aga Khan Hospital', 'Dar es Salaam', 'Ocean Road', 'pf2.jpg', 'admin.agakhan@mediconnect.test'],
            ['Kigamboni Community Hospital', 'Dar es Salaam', 'Kigamboni', 'pf3.jpg', 'admin.kigamboni@mediconnect.test'],
            ['Benjamin Mkapa Hospital', 'Dodoma', 'Swaswa', 'pf4.jpg', 'admin.mkapa@mediconnect.test'],
        ];

        $doctorNames = [
            'Dr. Neema Mushi', 'Dr. Juma Kileo', 'Dr. Amina Salim', 'Dr. Peter Mwakyusa',
            'Dr. Fatma Hassan', 'Dr. Daniel Msuya', 'Dr. Grace Lyimo', 'Dr. Said Mrisho',
        ];
        $photos = ['team1.jpg', 'team2.jpg', 'team3.jpg', 'team4.jpg'];
        $n = 0;

        foreach ($hospitals as $i => [$name, $city, $address, $image, $adminEmail]) {
            $admin = User::updateOrCreate(['email' => $adminEmail], [
                'name' => 'Admin '.Str::before($name, ' '), 'phone' => '+25570000010'.$i, 'password' => 'password', 'role' => UserRole::HospitalAdmin,
            ]);

            $hospital = Hospital::updateOrCreate(['slug' => Str::slug($name)], [
                'admin_id' => $admin->id, 'name' => $name, 'city' => $city, 'address' => $address,
                'verified_at' => now(), 'subscription_ends_at' => now()->addYear(),
                'services' => ['General Medicine', 'Laboratory', 'Pharmacy', 'Maternity'], 'insurances' => ['NHIF', 'AAR', 'Jubilee'],
                'registration_number' => 'REG-00'.($i + 1),
                'phone' => '+25522000000'.$i, 'email' => 'info'.$i.'@mediconnect.test', 'image' => $image,
                'description' => $name.' provides quality healthcare services in '.$city.', with experienced specialists and modern facilities.',
            ]);

            foreach ([0, 1] as $slot) {
                $fullName = $doctorNames[$n % count($doctorNames)];
                $user = User::updateOrCreate(['email' => 'doctor'.($n + 1).'@mediconnect.test'], [
                    'name' => $fullName, 'phone' => '+25571000000'.$n, 'password' => 'password', 'role' => UserRole::Doctor,
                ]);

                $doctor = Doctor::updateOrCreate(['user_id' => $user->id], [
                    'hospital_id' => $hospital->id,
                    'specialty_id' => $specialties[$n % $specialties->count()]->id,
                    'bio' => $fullName.' is a dedicated specialist with a passion for patient-centred care.',
                    'experience_years' => 5 + $n * 2,
                    'consultation_fee' => 30000 + ($n % 4) * 10000,
                    'photo' => $photos[$n % count($photos)],
                ]);

                foreach (range(1, 5) as $weekday) {
                    $doctor->schedules()->updateOrCreate(['weekday' => $weekday], [
                        'start_time' => '08:00', 'end_time' => '16:00', 'slot_minutes' => 30,
                    ]);
                }

                // Demo ratings (1–5 stars) from a few seeded patients.
                foreach (range(1, 3) as $k) {
                    $reviewer = User::updateOrCreate(['email' => "reviewer{$k}@mediconnect.test"], [
                        'name' => "Demo Patient {$k}", 'phone' => "+2557200000{$k}0", 'password' => 'password', 'role' => UserRole::Patient,
                    ]);
                    $doctor->ratings()->updateOrCreate(['user_id' => $reviewer->id], [
                        'rating' => 3 + (($n + $k) % 3),
                        'comment' => $k === 1 ? 'Very professional and caring.' : null,
                    ]);
                }

                $n++;
            }
        }
    }
}
