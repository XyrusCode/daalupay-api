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
            $table->uuid('uuid')->primary();
            $table->foreignUuid('user_id')->constrained('users', 'uuid')->onDelete('cascade');
            $table->foreignUuid('admin_id')->constrained('admins', 'uuid')->onDelete('cascade');
            $table->foreignUuid('transaction_id')->constrained('transactions', 'uuid')->onDelete('cascade');
            $table->string('from_currency');
            $table->string('to_currency');
            $table->decimal('from_amount', 15, 8);
            $table->decimal('to_amount', 15, 8);
            $table->decimal('rate', 15, 8);
            $table->string('status');
            $table->text('notes')->nullable();
            $table->timestamps();
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
