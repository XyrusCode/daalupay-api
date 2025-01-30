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
        // add wallet_id column to receipts table linked to wallets table
        Schema::table('receipts', function (Blueprint $table) {
            $table->unsignedBigInteger('wallet_id')->nullable();
            $table->foreign('wallet_id')->references('id')->on('wallets');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // drop wallet_id column from receipts table
        Schema::table('receipts', function (Blueprint $table) {
            $table->dropColumn('wallet_id');
        });
    }
};
