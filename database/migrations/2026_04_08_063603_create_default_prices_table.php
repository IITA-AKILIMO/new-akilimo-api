<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('default_prices', function (Blueprint $table) {
            $table->id();
            $table->string('country', 2);
            $table->string('item', 50);
            $table->double('price');
            $table->string('unit', 15)->default('per_bag');
            $table->string('currency', 3)->nullable();
            $table->unique(['country', 'item'], 'country_item_uk');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('default_prices');
    }
};
