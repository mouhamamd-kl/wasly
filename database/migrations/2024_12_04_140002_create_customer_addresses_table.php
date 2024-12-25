<?php

use App\Models\Customer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id(); // Primary key
        
            // Foreign key for Customer
            $table->foreignIdFor(Customer::class); 
        
            // Address details
            $table->enum('label', ['Home', 'Office','other'])->default('Home');
            $table->decimal('longitude'); // Longitude of the address
            $table->decimal('latitude'); // Latitude of the address
        
            // Default flag
            $table->boolean('is_default'); // Indicates if the address is the default
        
            // Timestamps
            $table->timestamps(); // Includes `created_at` and `updated_at`
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_addresses');
    }
};
