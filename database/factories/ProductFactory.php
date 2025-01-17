<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => ucfirst($this->faker->words(2, true)), // Capitalizes the first word

            'photo' => uploadToImgurLocal('defualts\images\defaultProductImage.png'), // Random product image
            'description' => $this->faker->sentence(10), // Random product description
            'stock_quantity' => $this->faker->numberBetween(1, 1000), // Random stock quantity
            'price' => $this->faker->randomFloat(2, 1, 1000), // Random price between 1 and 1000
            'is_active' => $this->faker->boolean(80), // 80% chance to be active
            'category_id' => Category::inRandomOrder()->value('id') ?? Category::factory(), // Random existing or new category
            'store_id' => Store::inRandomOrder()->value('id') ?? Store::factory(), // Random existing or new store
        ];
    }
}
