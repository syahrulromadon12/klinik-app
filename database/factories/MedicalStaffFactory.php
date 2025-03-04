<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MedicalStaffFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'user_id' => \App\Models\User::factory(),
            'license_number' => 'SIP-' . $this->faker->numerify('##########'),
            'specialization' => $this->faker->randomElement([
                'Cardiology', 'Dermatology', 'Neurology', 
                'Pediatrics', 'Psychiatry', 'Radiology', 'Surgery'
            ]),
            'work_schedule' => json_encode([
                'monday'    => $this->generateRandomSchedule(),
                'tuesday'   => $this->generateRandomSchedule(),
                'wednesday' => $this->generateRandomSchedule(),
                'thursday'  => $this->generateRandomSchedule(),
                'friday'    => $this->generateRandomSchedule(),
                'saturday'  => $this->generateRandomSchedule(),
                'sunday'    => [] // Biasanya hari Minggu libur
            ]),
            'experience_years' => $this->faker->numberBetween(1, 30),
            'education' => $this->faker->randomElement([
                'S1', 'S2', 'S3', 'Profesi'
            ]),
            'consultation_fee' => $this->faker->numberBetween(100000, 500000),
            'clinic_id' => \App\Models\Clinic::inRandomOrder()->first()->id,
        ];
    }

    private function generateRandomSchedule()
    {
        return $this->faker->randomElement([
            ["08:00-12:00", "14:00-18:00"], // Pagi & Sore
            ["08:00-12:00"],               // Pagi saja
            ["14:00-18:00"],               // Sore saja
            []                             // Libur hari itu
        ]);
    }
}
