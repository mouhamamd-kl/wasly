<?php

use App\Models\Category;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('photo')->nullable();
            $table->string('description');
            $table->bigInteger('stock_quantity'); // Fixed typo from "stoack_quantity"
            $table->decimal('price');
            $table->boolean('is_active');
            
            // Foreign keys
            $table->foreignIdFor(Category::class)->constrained('categories')->onDelete('cascade');
            $table->foreignIdFor(Store::class)->constrained('stores')->onDelete('cascade');
            
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
