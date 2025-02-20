<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Prescription>
 */
class PrescriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'medical_staff_id' => \App\Models\MedicalStaff::factory(),
            'clinic_id' => \App\Models\Clinic::factory(),
            'patient_id' => \App\Models\Patients::factory(),
            'diagnosis' => $this->faker->sentence,
            'notes' => $this->faker->sentence,
        ];
    }
}
