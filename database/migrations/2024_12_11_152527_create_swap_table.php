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
        Schema::create('swap_operations', function (Blueprint $table) {
             $table->id()->primary();
             $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('admin_id')->constrained('admins', 'id')->onDelete('cascade');
            $table->foreignId('transaction_id')->constrained('transactions', 'id')->onDelete('cascade');
            $table->string('from_currency');
            $table->string('to_currency');
            $table->decimal('from_amount', 15, 8);
            $table->decimal('to_amount', 15, 8);
            $table->decimal('rate', 15, 8);
            $table->string('status');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('swap_operations');
    }
};
