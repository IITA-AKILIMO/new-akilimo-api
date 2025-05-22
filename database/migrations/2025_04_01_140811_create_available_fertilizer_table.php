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
        Schema::create('available_fertilizer', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('name', 50)->unique('name');
            $table->string('type', 50)->unique('type');
            $table->integer('n_content');
            $table->integer('p_content');
            $table->integer('k_content');
            $table->integer('weight')->default(50);
            $table->decimal('price', 10)->default(0);
            $table->string('country', 5)->default('ALL');
            $table->string('use_case', 5)->nullable()->default('ALL');
            $table->boolean('available')->nullable()->default(false);
            $table->boolean('custom')->nullable()->default(false);
            $table->integer('sort_order')->nullable()->default(999);
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->dateTime('updated_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('available_fertilizer');
    }
};
