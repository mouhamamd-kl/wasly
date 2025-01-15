<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderStatus;
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
        // Get a random product or create a new one
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create();

        // Generate a random quantity
        $quantity = $this->faker->numberBetween(1, 5);

        // Calculate price based on product price
        $price = $quantity * $product->price;

        return [
            'order_id' => Order::inRandomOrder()->value('id') ?? Order::factory()->create()->id,
            'product_id' => $product->id, // Use the ID of the product
            'quantity' => $quantity,
            'order_status_id' => OrderStatus::inRandomOrder()->value('id') ?? OrderStatus::factory()->create()->id,
            'price' => $price,
        ];
    }
}
