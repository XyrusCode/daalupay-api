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
        Schema::create('transactions', function (Blueprint $table) {
             $table->id()->primary();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained('users', 'id')->onDelete('cascade');
            $table->string('reference_number');
            $table->enum('channel', ['paystack', 'flutterwave', 'alipay', 'paypal']);
            $table->enum('type', ['deposit', 'withdrawal', 'swap']);
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'completed', 'failed']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction');
    }
};
