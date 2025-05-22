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
        Schema::create('request_fertilizer', function (Blueprint $table) {
            $table->bigInteger('fertilizer_id', true);
            $table->bigInteger('request_id')->nullable();
            $table->string('fertilizer_type', 100);
            $table->boolean('available')->nullable()->default(false);
            $table->decimal('price', 10);
            $table->decimal('weight', 10);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_fertilizer');
    }
};
