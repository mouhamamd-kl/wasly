<?php

namespace Database\Factories;

use App\Models\StoreOwner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'store_owner_id' => StoreOwner::inRandomOrder()->value('id') ?? StoreOwner::factory(), // Choose a random existing StoreOwner or create one if none exist
            'name' => $this->faker->company, // Random store name
            'photo' => $this->faker->imageUrl(640, 480, 'business', true, 'store'), // Random store photo URL
            'phone' => $this->faker->unique()->phoneNumber(),
            'latitude' => $this->faker->latitude(-90, 90), // Random latitude
            'longitude' => $this->faker->longitude(-180, 180), // Random longitude
        ];
    }
}
