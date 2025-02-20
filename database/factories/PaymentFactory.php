<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
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
            'appointment_id' => \App\Models\Appointment::factory(),
            'payment_amount' => $this->faker->randomFloat(2, 1, 100),
            'payment_status' => $this->faker->randomElement(['not yet paid', 'paid', 'failed']),
        ];
    }
}
