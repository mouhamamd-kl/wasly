<?php

namespace Database\Factories;

use App\Models\Order;
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
            'order_id' => Order::inRandomOrder()->value('id') ?? Order::factory(),
            'payment_status_id' => PaymentStatus::inRandomOrder()->value('id') ?? PaymentStatus::factory(),
            'payment_method_id' => PaymentMethod::inRandomOrder()->value('id') ?? PaymentMethod::factory(),
            'amount' => $this->faker->randomFloat(2, 10, 500), // Random amount between 10 and 500
        ];
    }
}
