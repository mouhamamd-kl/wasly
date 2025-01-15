<?php

namespace Database\Seeders;

use App\Models\CustomerCard;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            // Seeders in numerical order
            // UserSeeder::class,                   // 1

            AdminSeeder::class,
            CustomerSeeder::class,               // 2
            CustomerAddressSeeder::class,         // 3
            CustomerCardSeeder::class,
            CategorySeeder::class,               // 4
            StoreOwnerSeeder::class,             // 5
            StoreSeeder::class,                  // 6
            DeliveryStatusSeeder::class,         // 7
            DeliverySeeder::class,               // 8
            ProductSeeder::class,                // 9
            CartSeeder::class,                   // 10
            FavouriteProductSeeder::class,       // 11
            PromoCodeSeeder::class,              // 12
            RatingSeeder::class,                 // 13
            ReviewSeeder::class,                 // 14
            OrderStatusSeeder::class,            // 15

            PaymentMethodSeeder::class,          // 18
            PaymentStatusSeeder::class,          // 19

            OrderSeeder::class,                  // 16
            OrderItemSeeder::class,              // 17
            PaymentSeeder::class,                // 20
        ]);
    }
}
