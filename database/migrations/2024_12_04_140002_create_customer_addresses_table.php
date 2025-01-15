<?php

use App\Models\Customer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Customer::class)->constrained()->onDelete('cascade');
            $table->enum('label', ['Home', 'Office', 'Other'])->default('Home');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            // Apply unique constraint only when is_default = 1
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_addresses');
    }
};
