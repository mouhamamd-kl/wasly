<?php

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\User;
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
        Schema::create('order_items', function (Blueprint $table) {
            // Primary Key
            $table->id();
            // Foreign Keys
            $table->foreignIdFor(Order::class)->constrained('orders')->onDelete('cascade');;
            $table->foreignIdFor(Product::class)->constrained('products')->onDelete('cascade');;
            $table->foreignIdFor(OrderStatus::class)->constrained('order_statuses')->onDelete('cascade');;
            // Item details
            $table->bigInteger('quantity');
            $table->decimal('price');

            // Default timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
