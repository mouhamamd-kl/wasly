<?php

namespace Database\Seeders;

use App\Models\CustomerCard;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CustomerCard::factory()->count(10)->create(); // Generate 10 fake customer card
    }
}
