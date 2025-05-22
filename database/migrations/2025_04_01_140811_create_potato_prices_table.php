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
        Schema::create('potato_prices', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('country', 4);
            $table->decimal('min_local_price', 10)->default(0);
            $table->decimal('max_local_price', 10)->default(0);
            $table->decimal('min_usd', 10)->default(0);
            $table->decimal('max_usd', 10)->default(0);
            $table->boolean('min_price')->default(false);
            $table->boolean('max_price')->default(false);
            $table->boolean('price_active')->nullable()->default(false);
            $table->integer('sort_order')->default(1);
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->dateTime('updated_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('potato_prices');
    }
};
