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
        Schema::create('carts', function (Blueprint $table) {
            // Primary Key
            $table->id();
        
            // Foreign Keys
            $table->foreignIdFor(Customer::class)
                ->constrained('customers')
                ->onDelete('cascade');
            $table->foreignIdFor(Product::class)
                ->constrained('products')
                ->onDelete('cascade');
        
            // Cart Item Details
            $table->bigInteger('count');
        
            // Default Timestamps
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
