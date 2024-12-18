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
        Schema::create('user_settings', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->foreignUuid('user_id')->constrained('users', 'uuid')->onDelete('cascade');
            // 2fa settings
            $table->boolean('two_factor_enabled')->default(false);
            $table->string('two_factor_method')->nullable();
            $table->string('two_factor_phone')->nullable();
            $table->string('two_factor_email')->nullable();
            // transaction limit
            $table->decimal('transaction_limit', 10, 2)->default(50000);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_settings');
    }
};
