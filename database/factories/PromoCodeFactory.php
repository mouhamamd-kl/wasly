<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PromoCode>
 */
class PromoCodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->unique()->bothify('PROMO###??')), // Generates unique codes like PROMO123AB
            'discount' => $this->faker->randomFloat(2, 5, 50), // Random discount between 5% and 50%
            'expiration_date' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'), // Expiration date up to 1 year from now
            'max_uses' => $this->faker->numberBetween(1, 1000), // Random max uses between 1 and 1000
        ];
    }
}
