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
        Schema::create('api_users', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('fullname');
            $table->string('phone')->unique(); // Ensure phone is unique
            $table->string('email')->unique();
            $table->string('password');
            $table->string('image')->nullable();
            $table->string('provider_id')->nullable();
            $table->string('mobile_verfi_otp')->nullable(); // Store OTP or verification status
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_users');
    }
};
