<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patients>
 */
class PatientsFactory extends Factory
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
            'kis_number' => $this->faker->unique()->numerify('################'),
            'nik_number' => $this->faker->unique()->numerify('################'),
            'name' => $this->faker->name,
            'date_of_birth' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            'blood_type' => $this->faker->randomElement(['A', 'B', 'AB', 'O']),
            'allergy' => $this->faker->word,
            'job' => $this->faker->jobTitle,
            'email' => $this->faker->email,
            'phone_number' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
        ];
    }
}
