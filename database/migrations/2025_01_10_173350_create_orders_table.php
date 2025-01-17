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
        Schema::disableForeignKeyConstraints();

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->decimal('total_amount', 10, places: 2);
            $table->string('payment_method')->nullable(); // e.g., 'credit_card', 'paypal', etc.
            $table->string('transaction_id')->nullable(); // e.g., payment gateway transaction ID
            $table->enum('status', ["pending","accepted","delivering","delivered","cancelled"])->default('pending');
            $table->text('delivery_location');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
