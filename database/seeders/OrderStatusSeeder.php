<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Predefined order statuses
         $statuses = [
            'Pending',
            'Processed',
            'Shipped',
            'Delivered',
            'Cancelled',
            'Returned',
            'Out for Delivery',
        ];

        // Create each order status in the database
        foreach ($statuses as $status) {
            OrderStatus::create(['name' => $status]);
        }
    }
}
