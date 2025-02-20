<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Medicine>
 */
class MedicineFactory extends Factory
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
            'name' => $this->faker->name,
            'medical_category_id' => \App\Models\MedicalCategory::factory(),
            'stock' => $this->faker->randomNumber(),
            'price' => $this->faker->randomFloat(2, 1, 100),
        ];
    }
}
