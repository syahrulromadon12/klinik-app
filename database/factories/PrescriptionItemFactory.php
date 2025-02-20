<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PrescriptionItem>
 */
class PrescriptionItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => \Str::uuid(),
            'prescription_id' => \App\Models\Prescription::factory(),
            'medicine_id' => \App\Models\Medicine::factory(),
            'quantity' => $this->faker->randomNumber(),
        ];
    }
}
