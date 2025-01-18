<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [

            [
                "id" => 1,
                "name" => "Wireless Earbuds",
                "photo" => "https=>//imgur.com/example1",
                "description" => "High-quality wireless earbuds with noise cancellation.",
                "stock_quantity" => 150,
                "price" => 99.99,
                "is_active" => true,
                "category_id" => 1,
                "store_id" => 1
            ],
            [
                "id" => 2,
                "name" => "Smartphone",
                "photo" => "https=>//imgur.com/example2",
                "description" => "Latest model smartphone with high-speed processor.",
                "stock_quantity" => 100,
                "price" => 699.99,
                "is_active" => true,
                "category_id" => 1,
                "store_id" => 2
            ],
            [
                "id" => 3,
                "name" => "Leather Jacket",
                "photo" => "https=>//imgur.com/example3",
                "description" => "Stylish leather jacket for all seasons.",
                "stock_quantity" => 50,
                "price" => 199.99,
                "is_active" => true,
                "category_id" => 2,
                "store_id" => 3
            ],
            [
                "id" => 4,
                "name" => "Sneakers",
                "photo" => "https=>//imgur.com/example4",
                "description" => "Comfortable and trendy sneakers.",
                "stock_quantity" => 200,
                "price" => 89.99,
                "is_active" => true,
                "category_id" => 2,
                "store_id" => 4
            ],
            [
                "id" => 5,
                "name" => "Blender",
                "photo" => "https=>//imgur.com/example5",
                "description" => "High-power blender for smoothies and shakes.",
                "stock_quantity" => 70,
                "price" => 49.99,
                "is_active" => true,
                "category_id" => 3,
                "store_id" => 1
            ],
            [
                "id" => 6,
                "name" => "Cookware Set",
                "photo" => "https=>//imgur.com/example6",
                "description" => "Non-stick cookware set for all your cooking needs.",
                "stock_quantity" => 30,
                "price" => 129.99,
                "is_active" => true,
                "category_id" => 3,
                "store_id" => 5
            ],
            [
                "id" => 7,
                "name" => "Yoga Mat",
                "photo" => "https=>//imgur.com/example7",
                "description" => "Durable yoga mat for home workouts.",
                "stock_quantity" => 120,
                "price" => 19.99,
                "is_active" => true,
                "category_id" => 4,
                "store_id" => 6
            ],
            [
                "id" => 8,
                "name" => "Basketball",
                "photo" => "https=>//imgur.com/example8",
                "description" => "High-quality basketball for indoor and outdoor play.",
                "stock_quantity" => 80,
                "price" => 29.99,
                "is_active" => true,
                "category_id" => 4,
                "store_id" => 4
            ],
            [
                "id" => 9,
                "name" => "Fiction Novel",
                "photo" => "https=>//imgur.com/example9",
                "description" => "Best-selling fiction novel with an engaging storyline.",
                "stock_quantity" => 300,
                "price" => 14.99,
                "is_active" => true,
                "category_id" => 5,
                "store_id" => 7
            ],
            [
                "id" => 10,
                "name" => "Self-Help Book",
                "photo" => "https=>//imgur.com/example10",
                "description" => "Inspiring self-help book for personal growth.",
                "stock_quantity" => 120,
                "price" => 19.99,
                "is_active" => true,
                "category_id" => 5,
                "store_id" => 8
            ],
            [
                "id" => 11,
                "name" => "Face Cream",
                "photo" => "https=>//imgur.com/example11",
                "description" => "Moisturizing face cream with natural ingredients.",
                "stock_quantity" => 250,
                "price" => 24.99,
                "is_active" => true,
                "category_id" => 6,
                "store_id" => 9
            ],
            [
                "id" => 12,
                "name" => "Hair Dryer",
                "photo" => "https=>//imgur.com/example12",
                "description" => "Compact hair dryer with multiple settings.",
                "stock_quantity" => 80,
                "price" => 39.99,
                "is_active" => true,
                "category_id" => 6,
                "store_id" => 10
            ],
            [
                "id" => 13,
                "name" => "Board Game",
                "photo" => "https=>//imgur.com/example13",
                "description" => "Fun board game for family and friends.",
                "stock_quantity" => 150,
                "price" => 34.99,
                "is_active" => true,
                "category_id" => 7,
                "store_id" => 2
            ],
            [
                "id" => 14,
                "name" => "Puzzle Set",
                "photo" => "https=>//imgur.com/example14",
                "description" => "Challenging puzzle set for all ages.",
                "stock_quantity" => 100,
                "price" => 14.99,
                "is_active" => true,
                "category_id" => 7,
                "store_id" => 3
            ],
            [
                "id" => 15,
                "name" => "Organic Rice",
                "photo" => "https=>//imgur.com/example15",
                "description" => "High-quality organic rice for daily meals.",
                "stock_quantity" => 500,
                "price" => 9.99,
                "is_active" => true,
                "category_id" => 8,
                "store_id" => 11
            ],
            [
                "id" => 16,
                "name" => "Coffee Beans",
                "photo" => "https=>//imgur.com/example16",
                "description" => "Premium coffee beans for a perfect brew.",
                "stock_quantity" => 200,
                "price" => 19.99,
                "is_active" => true,
                "category_id" => 8,
                "store_id" => 12
            ],
            [
                "id" => 17,
                "name" => "Dog Leash",
                "photo" => "https=>//imgur.com/example17",
                "description" => "Durable dog leash with comfortable grip.",
                "stock_quantity" => 100,
                "price" => 15.99,
                "is_active" => true,
                "category_id" => 9,
                "store_id" => 13
            ],
            [
                "id" => 18,
                "name" => "Cat Toy",
                "photo" => "https=>//imgur.com/example18",
                "description" => "Interactive toy for your playful cat.",
                "stock_quantity" => 200,
                "price" => 9.99,
                "is_active" => true,
                "category_id" => 9,
                "store_id" => 14
            ],
            [
                "id" => 19,
                "name" => "Laptop",
                "photo" => "https=>//imgur.com/example19",
                "description" => "Powerful laptop for work and entertainment.",
                "stock_quantity" => 40,
                "price" => 999.99,
                "is_active" => true,
                "category_id" => 1,
                "store_id" => 15
            ],
            [
                "id" => 20,
                "name" => "Fitness Tracker",
                "photo" => "https=>//imgur.com/example20",
                "description" => "Track your workouts and monitor your health.",
                "stock_quantity" => 300,
                "price" => 59.99,
                "is_active" => true,
                "category_id" => 4,
                "store_id" => 16
            ],
            [
                "id" => 21,
                "name" => "Action Camera",
                "photo" => "https=>//imgur.com/example21",
                "description" => "Compact action camera for outdoor adventures.",
                "stock_quantity" => 60,
                "price" => 199.99,
                "is_active" => true,
                "category_id" => 1,
                "store_id" => 17
            ],
            [
                "id" => 22,
                "name" => "Wrist Watch",
                "photo" => "https=>//imgur.com/example22",
                "description" => "Elegant wristwatch for daily use.",
                "stock_quantity" => 90,
                "price" => 49.99,
                "is_active" => true,
                "category_id" => 2,
                "store_id" => 18
            ],
            [
                "id" => 23,
                "name" => "Electric Kettle",
                "photo" => "https=>//imgur.com/example23",
                "description" => "Quick-boiling electric kettle.",
                "stock_quantity" => 70,
                "price" => 39.99,
                "is_active" => true,
                "category_id" => 3,
                "store_id" => 19
            ],
            [
                "id" => 24,
                "name" => "Camping Tent",
                "photo" => "https=>//imgur.com/example24",
                "description" => "Spacious tent for outdoor camping trips.",
                "stock_quantity" => 40,
                "price" => 99.99,
                "is_active" => true,
                "category_id" => 4,
                "store_id" => 20
            ],
            [
                "id" => 25,
                "name" => "Skin Serum",
                "photo" => "https=>//imgur.com/example25",
                "description" => "Revitalizing serum for glowing skin.",
                "stock_quantity" => 150,
                "price" => 29.99,
                "is_active" => true,
                "category_id" => 6,
                "store_id" => 21
            ],
            [
                "id" => 26,
                "name" => "Laptop Bag",
                "photo" => "https=>//imgur.com/example26",
                "description" => "Protective bag for your laptop.",
                "stock_quantity" => 100,
                "price" => 34.99,
                "is_active" => true,
                "category_id" => 1,
                "store_id" => 22
            ],
            [
                "id" => 27,
                "name" => "Children's Book",
                "photo" => "https=>//imgur.com/example27",
                "description" => "Illustrated book for children aged 5-10.",
                "stock_quantity" => 200,
                "price" => 12.99,
                "is_active" => true,
                "category_id" => 5,
                "store_id" => 23
            ],
            [
                "id" => 28,
                "name" => "Electric Drill",
                "photo" => "https=>//imgur.com/example28",
                "description" => "Versatile electric drill for home projects.",
                "stock_quantity" => 50,
                "price" => 89.99,
                "is_active" => true,
                "category_id" => 3,
                "store_id" => 24
            ],
            [
                "id" => 29,
                "name" => "Garden Hose",
                "photo" => "https=>//imgur.com/example29",
                "description" => "Flexible and durable garden hose.",
                "stock_quantity" => 120,
                "price" => 29.99,
                "is_active" => true,
                "category_id" => 8,
                "store_id" => 25
            ],
            [
                "id" => 30,
                "name" => "Bird Feeder",
                "photo" => "https=>//imgur.com/example30",
                "description" => "Attractive bird feeder for your garden.",
                "stock_quantity" => 100,
                "price" => 14.99,
                "is_active" => true,
                "category_id" => 9,
                "store_id" => 26
            ]

        ];
    }
}
