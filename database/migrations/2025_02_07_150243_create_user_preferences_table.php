<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->enum('notify_email', ['true', 'false'])->default('true');
            $table->enum('notify_sms', ['true', 'false'])->default('false');
            $table->enum('theme', ['light', 'dark'])->default('light');
            $table->enum('daily_transaction_limit', ['500000', 'unlimited'])->default('500000');
            $table->decimal('transaction_total_today', 10, 2)->default(0.00);
            $table->date('last_transaction_date')->nullable();
            $table->enum('two_fa_enabled', ['true', 'false'])->default('true');
            $table->timestamps();

            // Add a foreign key constraint linking to the users table.
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('user_preferences');
    }
}
