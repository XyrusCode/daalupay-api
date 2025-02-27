<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Currency name
            $table->string('code', 3); // ISO 3166-1 alpha-3 code
            $table->decimal('exchange_rate', 15, 8)->default(1.00000000); // Exchange rate against USD
            $table->enum('status', ['enabled', 'disabled'])->default('enabled'); // Currency status
            $table->foreignId('country_id')->constrained()->onDelete('cascade'); // Foreign key to countries
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('currencies');
    }
}
