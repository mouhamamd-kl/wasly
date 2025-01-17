<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electronics',
                'photo' => uploadToImgurLocal(defaultImagePath: 'defualts\images\categories\electronics.png'),
                'color' => '28, 39, 76, 1', // Blue
            ],
            [
                'name' => 'Fashion',
                'photo' => uploadToImgurLocal(defaultImagePath: 'defualts\images\categories\electronics.png'),
                'color' => '0, 0, 0, 1', // Pink
            ],
            [
                'name' => 'Home & Kitchen',
                'photo' => uploadToImgurLocal(defaultImagePath: 'defualts\images\categories\kitchen.png'),
                'color' => '111, 82, 81, 1', // Gold
            ],
            [
                'name' => 'Sports',
                'photo' => uploadToImgurLocal(defaultImagePath: 'defualts\images\categories\sports.png'),
                'color' => '0, 128, 192, 1', // Green
            ],
            [
                'name' => 'Books',
                'photo' => uploadToImgurLocal(defaultImagePath: 'defualts\images\categories\books.png'),
                'color' => '0, 128, 64, 1', // Purple
            ],
            [
                'name' => 'Beauty & Health',
                'photo' => uploadToImgurLocal(defaultImagePath: 'defualts\images\categories\beauty.png'),
                'color' => '255, 128, 255, 1', // Orange
            ],
            [
                'name' => 'Toys & Games',
                'photo' => uploadToImgurLocal(defaultImagePath: 'defualts\images\categories\games.png'),
                'color' => '170, 126, 222, 1', // Crimson
            ],
            [
                'name' => 'Groceries',
                'photo' => uploadToImgurLocal(defaultImagePath: 'defualts\images\categories\groceries.png'),
                'color' => '128, 128, 255, 1', // Medium Spring Green
            ],
            [
                'name' => 'Pets',
                'photo' => uploadToImgurLocal(defaultImagePath: 'defualts\images\categories\pets.png'),
                'color' => '192, 192, 192, 1', // Tomato
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                [
                    'name' => $category['name'],
                    'photo' => $category['photo'],
                    'color' => $category['color'],
                ]
            );
        }
    }
}
