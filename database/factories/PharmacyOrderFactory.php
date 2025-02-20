<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PharmacyOrder>
 */
class PharmacyOrderFactory extends Factory
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
            'prescription_id' => \App\Models\Prescription::factory(),
            'medical_staff_id' => \App\Models\MedicalStaff::factory(),
            'status' => $this->faker->randomElement(['preparing', 'calling', 'completed']),
        ];
    }
}
