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
        Schema::create('fertilizer_price', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('country', 4)->nullable();
            $table->string('fertilizer_key', 50)->nullable();
            $table->decimal('min_price', 10);
            $table->decimal('max_price', 10);
            $table->decimal('price_per_bag', 10);
            $table->boolean('price_active')->nullable()->default(false);
            $table->integer('sort_order')->default(999);
            $table->string('desc', 100)->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fertilizer_price');
    }
};
