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
        Schema::create('starch_factory', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('factory_name', 100);
            $table->string('factory_label', 120);
            $table->string('country', 4);
            $table->boolean('factory_active')->nullable()->default(false);
            $table->integer('sort_order')->nullable()->default(0);
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->dateTime('updated_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('starch_factory');
    }
};
