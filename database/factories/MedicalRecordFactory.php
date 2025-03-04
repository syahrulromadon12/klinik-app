<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MedicalRecord>
 */
class MedicalRecordFactory extends Factory
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
            'patient_id' => \App\Models\Patients::factory(),
            'medical_staff_id' => \App\Models\MedicalStaff::factory(),
            'clinic_id' => \App\Models\Clinic::factory(),
            'appointment_id' => \App\Models\Appointment::factory(),
            'prescription_id' => \App\Models\Prescription::factory(),
        ];
    }
}
