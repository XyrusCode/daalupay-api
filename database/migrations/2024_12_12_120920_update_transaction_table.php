<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Drop all foreign keys first
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'transactions'
            AND CONSTRAINT_TYPE = 'FOREIGN KEY'
        ");

        foreach ($foreignKeys as $foreignKey) {
            Schema::table('transactions', function (Blueprint $table) use ($foreignKey) {
                $table->dropForeign($foreignKey->CONSTRAINT_NAME);
            });
        }

        // Modify the columns
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('admin_id')->nullable()->change(); // No change here
            $table->unsignedBigInteger('user_id')->change(); // Ensure compatibility
        });

        // Add the new foreign keys
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign('admin_id')
                  ->references('id')
                  ->on('admins')
                  ->onDelete('set null');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        // Drop the new foreign keys
        Schema::table('transactions', function (Blueprint $table) {
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME
                FROM information_schema.TABLE_CONSTRAINTS
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'transactions'
                AND CONSTRAINT_TYPE = 'FOREIGN KEY'
            ");

            foreach ($foreignKeys as $foreignKey) {
                $table->dropForeign($foreignKey->CONSTRAINT_NAME);
            }
        });

        // Revert the columns
        Schema::table('transactions', function (Blueprint $table) {
            $table->char('admin_id', 36)->nullable()->change(); // Original state
            $table->unsignedBigInteger('user_id')->change(); // Ensure match
        });
    }
};
