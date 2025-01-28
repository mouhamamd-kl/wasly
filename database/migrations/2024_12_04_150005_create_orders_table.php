<?php

use App\Models\Customer;
use App\Models\Delivery;
use App\Models\OrderStatus;
use App\Models\Store;
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
        Schema::create('orders', function (Blueprint $table) {
            // Primary Key
            $table->id();
        
            // Foreign Keys
            $table->foreignIdFor(Store::class)->constrained('stores')->onDelete('cascade');
            $table->foreignIdFor(Customer::class)->constrained('customers')->onDelete('cascade');;
            $table->foreignIdFor(Delivery::class)->constrained('deliveries')->onDelete('cascade');;
            $table->foreignIdFor(OrderStatus::class)->constrained('order_statuses')->onDelete('cascade');
        
            // Timestamps for events
            $table->timestamp('order_placed_at');
            $table->timestamp('order_delivered_at');
        
            // Default timestamps
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
