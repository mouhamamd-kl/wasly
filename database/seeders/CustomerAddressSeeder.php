<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerAddress;
use Illuminate\Database\Seeder;

class CustomerAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::all();

        foreach ($customers as $customer) {
            // Ensure there is only one default address
            $defaultExists = CustomerAddress::where('customer_id', $customer->id)
                ->where('is_default', true)
                ->exists();
        
            if (!$defaultExists) {
                CustomerAddress::factory()->default()->create([
                    'customer_id' => $customer->id,
                ]);
            }
        
            // Create additional non-default addresses
            CustomerAddress::factory()->count(2)->create([
                'customer_id' => $customer->id,
                'is_default' => false,
            ]);
        }
        
    }
    
}