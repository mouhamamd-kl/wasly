<?php

namespace Database\Seeders;

use App\Models\StoreOwner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreOwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StoreOwner::factory()->count(10)->create(); 
    }
}
