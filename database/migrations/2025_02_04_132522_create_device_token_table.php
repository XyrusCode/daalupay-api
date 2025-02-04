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
        Schema::create('device_token', function (Blueprint $table) {
            $table->id();
            $table->string('token');
            // 1 usser can have multiple device tokens
            $table->foreignId('user_id')->constrained('users');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->enum('device_type', ['ios', 'android', 'web']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_token');
    }
};
