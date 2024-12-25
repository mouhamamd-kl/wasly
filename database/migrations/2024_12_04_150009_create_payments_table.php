<?php

use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\PaymentStatus;
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
        Schema::create('payments', function (Blueprint $table) {
            // Primary Key
            $table->id();
        
            // Foreign Keys
            $table->foreignIdFor(Order::class)->constrained('orders')->onDelete('cascade');;
            $table->foreignIdFor(PaymentStatus::class)->constrained('payment_statuses')->onDelete('cascade');
            $table->foreignIdFor(PaymentMethod::class)->constrained('payment_methods')->onDelete('cascade');
        
            // Payment details
            $table->decimal('amount');
        
            // Default timestamps
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
