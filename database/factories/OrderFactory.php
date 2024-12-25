<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Delivery;
use App\Models\OrderStatus;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'store_id' => Store::inRandomOrder()->value('id') ?? Store::factory(), // Random store
            'customer_id' => Customer::inRandomOrder()->value('id') ?? Customer::factory(), // Random customer
            'delivery_id' => Delivery::inRandomOrder()->value('id') ?? Delivery::factory(), // Random delivery
            'order_status_id' => OrderStatus::inRandomOrder()->value('id') ?? OrderStatus::factory(), // Random order status
            'order_placed_at' => $this->faker->dateTimeThisYear(), // Random date/time for order placed
            'order_delivered_at' => $this->faker->dateTimeThisYear(), // Random date/time for order delivered
        ];
    }
}
