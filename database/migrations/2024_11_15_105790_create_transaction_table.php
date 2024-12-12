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
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('reference_number');
            $table->string('channel');
            $table->decimal('amount', 10, 2);
            $table->string('send_currency');
            $table->string('receive_currency');
            $table->decimal('rate', 10, 2);
            $table->decimal('fee', 10, 2);
            $table->date('transaction_date');
            $table->enum('status', ['pending', 'completed', 'failed']);
            $table->foreignId('payment_id')->constrained('payments');
            $table->foreignId('admin_id')->constrained('admins');
            $table->timestamps();
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
