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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('admin_id')->constrained('admins', 'id')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('currency');
            $table->enum('status', ['pending', 'completed', 'rejected']);
            $table->string('payment_details');
            $table->string('recipient_name');
            $table->string('recipient_email');
            $table->text('description');
            $table->foreignId('transaction_id')->nullable()->constrained()->onDelete('set null');
            $table->string('document_type');
            $table->string('proof_of_payment');
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alipay_payment');
    }
};
