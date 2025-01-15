<?php

use App\Models\Customer;
use App\Models\Product;
use App\Models\Rating;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Pest\ArchPresets\Custom;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id(); // Primary key
        
            // Relationships
            $table->foreignIdFor(Customer::class)->constrained('customers')->onDelete('cascade'); // Foreign key to customers table
            $table->foreignIdFor(Product::class)->constrained('products')->onDelete('cascade'); // Foreign key to products table
            $table->foreignIdFor(Rating::class)->constrained('ratings')->onDelete('cascade'); // Foreign key to ratings table
        
            // Review details
            $table->text('description'); // Review description
        
            // Timestamps
            $table->timestamps(); // created_at and updated_at
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
