<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerCard>
 */
class CustomerCardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::inRandomOrder()->value('id') ?? Customer::factory(), // Random customer ID
            'card_number' => $this->faker->creditCardNumber, // Fake credit card number (placeholder)
            'expiration_date' => $this->faker->date('m/y'), // Random expiration date in m/y format
            'card_type' => $this->faker->randomElement(['Visa', 'MasterCard', 'American Express']), // Random card type
            'cvv' => $this->faker->numberBetween(100, 999), // Fake CVV (placeholder)
        ];
    }
}
