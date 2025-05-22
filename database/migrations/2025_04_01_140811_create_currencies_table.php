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
        Schema::create('currencies', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('country_code', 4)->nullable();
            $table->string('country', 50)->nullable();
            $table->string('currency_name', 80)->nullable();
            $table->string('currency_code', 50)->nullable();
            $table->string('currency_symbol', 50)->nullable();
            $table->string('currency_native_symbol', 50)->nullable();
            $table->string('name_plural', 100)->nullable();
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->dateTime('updated_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
