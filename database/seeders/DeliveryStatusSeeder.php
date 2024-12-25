<?php

namespace Database\Seeders;

use App\Models\DeliveryStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeliveryStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          // Predefined statuses for delivery personnel
          $statuses = [
            'Available',
            'On Delivery',
            'Break',
            'Offline',
        ];

        // Create each status in the database
        foreach ($statuses as $status) {
            DeliveryStatus::create(['name' => $status]);
        }
    }
}
