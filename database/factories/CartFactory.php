<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cart>
 */
class CartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get a random product with its stock quantity
        $product = Product::inRandomOrder()->first();

        // Ensure the quantity in the cart is not greater than the product's stock quantity
        $quantity = $this->faker->numberBetween(1, $product->stock_quantity);

        return [
            'customer_id' => Customer::inRandomOrder()->value('id') ?? Customer::factory(), // Random customer
            'product_id' => $product->id, // Random product
            'count' => $quantity, // Random count between 1 and the product's stock quantity
        ];
    }
}
