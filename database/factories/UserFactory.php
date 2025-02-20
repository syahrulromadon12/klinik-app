<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'phone_number' => $this->faker->phoneNumber,
            'password' => Hash::make('password'),
            'date_of_birth' => $this->faker->date,
            'address' => $this->faker->address,
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            'role_id' => \App\Models\Role::inRandomOrder()->first()->id,
            'remember_token' => Str::random(10),
            'photo_path' => $this->faker->imageUrl(),
            'nik_number' => $this->faker->unique()->randomNumber(),
            'kis_number' => $this->faker->unique()->randomNumber(),
            'blood_type' => $this->faker->randomElement(['A', 'B', 'AB', 'O', 'unknown']),
            'emergency_contact_name' => $this->faker->name,
            'emergency_contact_phone' => $this->faker->phoneNumber,
            'insurance_number' => $this->faker->unique()->randomNumber(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
