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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->integer('Trending')->default(0);
            $table->text('location')->nullable();
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->string('image', length: 255)->nullable();
            $table->enum('category', ["clothing","electronics","grocery","restaurant","beauty","furniture"]);
            $table->timestamps();
            $table->softDeletes();
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
