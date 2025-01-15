<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\CustomerAddress;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerAddress>
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
            'label' => $this->faker->randomElement(['Home', 'Office', 'Other']),
            'longitude' => $this->faker->longitude(-180, 180),
            'latitude' => $this->faker->latitude(-90, 90),
            'is_default' => false,
        ];
    }

    /**
     * Indicate that the address is the default for the customer.
     */
    public function default(): self
    {
        return $this->state(function (array $attributes) {
            $customerId = $attributes['customer_id'] ?? Customer::factory()->create()->id;

            // Ensure no duplicate default addresses for the customer
            if (CustomerAddress::where('customer_id', $customerId)->where('is_default', true)->exists()) {
                throw new Exception("Default address already exists for customer {$customerId}");
            }

            return ['is_default' => true];
        });
    }
}
