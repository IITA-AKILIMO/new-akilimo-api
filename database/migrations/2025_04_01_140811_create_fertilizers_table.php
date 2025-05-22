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
        Schema::create('fertilizers', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('name', 50);
            $table->string('type', 50);
            $table->string('fertilizer_key', 50)->nullable()->unique('country-fertilizer');
            $table->integer('weight')->default(50);
            $table->string('country', 3);
            $table->integer('sort_order')->nullable()->default(1);
            $table->string('use_case', 10)->default('ALL');
            $table->boolean('available')->nullable()->default(false);
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fertilizers');
    }
};
