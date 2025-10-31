<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produce_prices', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('country', 4);
            $table->string('produce_name', 20);
            $table->decimal('min_price', 10, 3)->default(0);
            $table->decimal('max_price', 10, 3)->default(0);
            $table->boolean('is_min_price')->default(false);
            $table->boolean('is_max_price')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produce_prices');
    }
};
