<?php

use App\Models\Customer;
use App\Models\Product;
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
        Schema::create('favourite_products', function (Blueprint $table) {
            $table->id(); // Primary key
        
            // Foreign key for Customer
            $table->foreignIdFor(Customer::class)->constrained('customers')->onDelete('cascade'); 
        
            // Foreign key for Product
            $table->foreignIdFor(Product::class)->constrained('products')->onDelete('cascade');;
        
            // Timestamps
            $table->timestamps(); // Includes `created_at` and `updated_at`
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favourite_products');
    }
};
