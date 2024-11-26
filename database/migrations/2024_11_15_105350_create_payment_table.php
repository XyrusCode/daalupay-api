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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('channel');
            $table->enum('type', ['deposit', 'withdrawal']);
            $table->decimal('amount', 10, 2); // Ensure 'amount' is of type DECIMAL
            $table->enum('status', ['pending', 'approved', 'rejected']);
            $table->string('method'); // Add method as a string if it's missing
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
};
