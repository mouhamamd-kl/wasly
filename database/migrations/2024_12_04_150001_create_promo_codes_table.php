<?php

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
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id(); // Primary key
        
            // Promo code details
            $table->string('code'); // Unique promo code
            $table->decimal('discount', 5, 2); // Discount percentage or amount
            $table->date('expiration_date'); // Expiration date
            $table->bigInteger('max_uses'); // Maximum allowed uses
        
            // Timestamps
            $table->timestamps(); // created_at and updated_at
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo_codes');
    }
};
