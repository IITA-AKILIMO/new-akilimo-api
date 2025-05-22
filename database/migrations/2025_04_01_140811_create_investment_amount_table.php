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
        Schema::create('investment_amount', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('country', 4)->nullable();
            $table->decimal('investment_amount', 10);
            $table->string('area_unit', 10)->nullable()->default('acre');
            $table->boolean('price_active')->nullable()->default(false);
            $table->integer('sort_order')->default(999);
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->dateTime('updated_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investment_amount');
    }
};
