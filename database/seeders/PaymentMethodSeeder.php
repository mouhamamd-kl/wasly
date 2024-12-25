<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = [
            'Credit Card',
            'Cash on Delivery',
        ];

        // Create each payment method in the database
        foreach ($methods as $method) {
            PaymentMethod::create(['name' => $method]);
        }
    }
}
