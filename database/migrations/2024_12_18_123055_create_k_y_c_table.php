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
        Schema::create('kyc', function (Blueprint $table) {
            $table->id()->primary();
            $table->foreignId('user_id')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('admin_id')->constrained('admins', 'id')->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('reason')->nullable();
            $table->enum('type', ['individual', 'company'])->default('individual');
            $table->string('passport_photo');
            $table->enum('document_type', ['passport', 'national_id', 'driver_license', 'voter_id', 'other'])->default('passport');
            $table->string('document_number');
            $table->string('document_image');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kyc');
    }
};
