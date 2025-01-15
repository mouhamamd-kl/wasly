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
            'Cancelled',
            'Rejected',
            'Accepted',
            'Partially Accepted'
        ];

        // Create each order status in the database
        foreach ($statuses as $status) {
            OrderStatus::create(['name' => $status]);
        }
    }
}
