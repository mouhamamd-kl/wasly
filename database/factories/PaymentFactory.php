<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_item_id' => OrderItem::inRandomOrder()->value('id') 
                ?? OrderItem::factory()->create()->id, // Fetch random or create a new order item
            'payment_status_id' => PaymentStatus::inRandomOrder()->value('id') 
                ?? PaymentStatus::factory()->create()->id, // Fetch random or create a new payment status
            'payment_method_id' => PaymentMethod::inRandomOrder()->value('id') 
                ?? PaymentMethod::factory()->create()->id, // Fetch random or create a new payment method
            'card_id' => $this->faker->creditCardNumber(), // Random card ID
            'amount' => $this->faker->randomFloat(2, 10, 500), // Random amount between 10 and 500
        ];
    }
}
