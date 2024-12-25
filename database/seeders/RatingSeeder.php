<?php

namespace Database\Seeders;

use App\Models\Rating;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          // Create ratings from 1.0 to 5.0 with halves
          for ($i = 1; $i <= 10; $i++) {
            Rating::create(['rating' => $i / 2]);
        }
    }
}
