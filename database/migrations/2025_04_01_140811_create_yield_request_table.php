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
        Schema::create('yield_request', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->decimal('map_lat', 12, 8);
            $table->decimal('map_long', 12, 8);
            $table->decimal('cassava_unit_weight', 10)->nullable();
            $table->decimal('cassava_unit_price', 10)->nullable();
            $table->decimal('max_investment', 10)->nullable();
            $table->decimal('field_area', 10)->nullable();
            $table->dateTime('planting_date');
            $table->dateTime('harvest_date');
            $table->string('country', 3);
            $table->string('client', 18)->nullable()->default('android');
            $table->string('area_units', 18)->nullable();
            $table->string('user_name', 18)->nullable();
            $table->string('user_phone_code', 5)->nullable();
            $table->string('user_phone_number', 18)->nullable();
            $table->string('cassava_pd', 18)->nullable();
            $table->string('field_description')->nullable();
            $table->string('user_email', 50)->nullable();
            $table->boolean('processed')->nullable()->default(false);
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->string('recommendation_text', 500)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yield_request');
    }
};
