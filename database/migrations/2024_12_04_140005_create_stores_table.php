<?php

use App\Models\StoreOwner;
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
        Schema::create('stores', function (Blueprint $table) {
            $table->id(); // Primary key

            // Relationships
            $table->foreignIdFor(StoreOwner::class)->constrained('store_owners')->onDelete('cascade'); // Foreign key to store owners
        
            // Store details
            $table->string('name'); // Store name
            $table->longText('photo')->nullable(); // Store photo
            $table->decimal('latitude', 10, 8); // Geographic latitude with precision
            $table->decimal('longitude', 11, 8); // Geographic longitude with precision
            $table->string('phone');
            // Timestamps
            $table->timestamps(); // created_at and updated_a
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
