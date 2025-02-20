<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Queue>
 */
class QueueFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'queue_number' => $this->faker->randomNumber(),
            'clinic_id' => \App\Models\Clinic::factory(),
            'status' => $this->faker->randomElement(['waiting', 'called', 'done']),
        ];
    }
}
