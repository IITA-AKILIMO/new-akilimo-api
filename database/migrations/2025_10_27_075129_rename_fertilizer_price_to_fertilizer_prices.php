<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('fertilizer_price', 'fertilizer_prices');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('fertilizer_prices', 'fertilizer_price');
    }
};
