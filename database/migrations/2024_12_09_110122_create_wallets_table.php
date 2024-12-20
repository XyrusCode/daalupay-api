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
        Schema::create('wallets', function (Blueprint $table) {
             $table->id()->primary();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained('users', 'id')->onDelete('cascade');
            $table->unsignedBigInteger('currency_id');
            $table->unsignedBigInteger('balance');
            // A user can have more than one wallet but only one per currency
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('restrict');
            $table->unique(['user_id', 'currency_id']);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
