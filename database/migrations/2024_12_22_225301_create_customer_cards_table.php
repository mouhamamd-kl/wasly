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
        Schema::create('customer_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Customer::class)->constrained()->onDelete('cascade'); // Associate with customer
            $table->string('card_number'); // Full card number (not recommended to store directly)
            $table->string('expiration_date'); // Change to string to store 'm/y' format
            $table->string('card_type'); // Visa, MasterCard, etc.
            $table->string('cvv'); // CVV code (not recommended to store)
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_cards');
    }
};
