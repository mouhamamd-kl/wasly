<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Rating;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::inRandomOrder()->value('id') ?? Customer::factory(), // Random or generated customer
            'product_id' => Product::inRandomOrder()->value('id') ?? Product::factory(),   // Random or generated product
            'rating_id' => Rating::inRandomOrder()->value('id') ?? Rating::factory(),      // Random or generated rating
            'description' => $this->faker->paragraph(),                                   // Random review text
        ];
    }
}
