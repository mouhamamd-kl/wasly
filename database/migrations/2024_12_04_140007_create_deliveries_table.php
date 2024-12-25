<?php

use App\Models\DeliveryStatus;
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
        Schema::create('deliveries', function (Blueprint $table) {
            // Primary Key
            $table->id();

            // Personal details
            $table->string('first_name');
            $table->string('last_name');
            $table->date('birth_date')->nullable(); // Nullable, for optional birth date
            $table->enum('gender', ['male', 'female'])->nullable(); // Gender with valid options

            // Contact information
            $table->string('phone')->unique(); // Unique phone numbers
            $table->string('email')->unique(); // Unique email addresses

            // Authentication and account details
            $table->string('password'); // Encrypted password
            $table->timestamp('email_verified_at')->nullable(); // Email verification timestamp
            $table->rememberToken(); // For "remember me" functionality

            // Additional attributes
            $table->longText('photo')->nullable(); // Nullable, for profile picture or file path

            $table->foreignIdFor(DeliveryStatus::class)->nullable()
                ->constrained('delivery_statuses')
                ->onDelete('cascade');

            // Delivery Location Details
            $table->decimal('current_latitude')->nullable();
            $table->decimal('current_longitude')->nullable();

            // Default Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
