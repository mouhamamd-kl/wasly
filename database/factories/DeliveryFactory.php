<?php

namespace Database\Factories;

use App\Models\DeliveryStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Delivery>
 */
class DeliveryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'birth_date' => $this->faker->dateTimeBetween('-60 years', '-18 years'),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'phone' => $this->faker->unique()->phoneNumber(),
            'chat_id' => null,
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'), // Default password
            'email_verified_at' => $this->faker->optional()->dateTimeBetween('-1 year', 'now'),
            'remember_token' => $this->faker->optional()->sha256(),
            'photo' => generateLocalAvatarPath(),
            'delivery_status_id' => DeliveryStatus::inRandomOrder()->value('id') ?? DeliveryStatus::factory(), // Random or generated delivery status

            'current_latitude' => $this->faker->latitude(-90, 90), // Random latitude
            'current_longitude' => $this->faker->longitude(-180, 180), // Random longitude

            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
