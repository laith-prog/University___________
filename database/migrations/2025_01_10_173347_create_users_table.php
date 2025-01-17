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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number', 20)->unique();
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('profile_image', 255)->nullable();
            $table->text('location')->nullable();
            $table->enum('role', ["user","admin"])->default('user');
            $table->timestamps();
            $table->string('password')->nullable();

            $table->softDeletes();
        });
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id');
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');

    }
};
