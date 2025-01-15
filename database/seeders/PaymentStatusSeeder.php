<?php

namespace Database\Seeders;

use App\Models\PaymentStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Predefined payment statuses
         $statuses = [
            'Pending',
            'Completed',
            'Cancelled',
        ];

        // Create each payment status in the database
        foreach ($statuses as $status) {
            PaymentStatus::create(['name' => $status]);
        }
    }
}
