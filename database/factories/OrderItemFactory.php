<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::inRandomOrder()->value('id') ?? Order::factory(), // Random order
            'product_id' => Product::inRandomOrder()->value('id') ?? Product::factory(), // Random product
            'quantity' => $this->faker->numberBetween(1, 5), // Random quantity between 1 and 5
            'price' => $this->faker->randomFloat(2, 10, 1000), // Random price between 10 and 1000
        ];
    }
}
