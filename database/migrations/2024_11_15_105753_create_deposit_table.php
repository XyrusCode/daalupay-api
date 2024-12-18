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
        Schema::create('deposits', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->timestamps();
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'approved', 'rejected']);
            $table->foreignUuid('user_id')->constrained('users', 'uuid')->onDelete('cascade');
            $table->foreignUuid('transaction_id')->constrained('transactions', 'uuid')->onDelete('cascade');
            $table->enum('channel', ['paystack', 'flutterwave', 'alipay', 'paypal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposit');
    }
};
