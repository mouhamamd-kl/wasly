<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerAdress>
 */
class CustomerAddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' =>  Customer::inRandomOrder()->value('id') ?? Customer::factory(), // Automatically creates a related Customer
            'label' => $this->faker->randomElement(['Home', 'Office', 'other']), // Random label
            'longitude' => $this->faker->longitude(-180, 180), // Random longitude within valid range
            'latitude' => $this->faker->latitude(-90, 90), // Random latitude within valid range
            'is_default' => $this->faker->boolean, // Random boolean
        ];
    }
}
