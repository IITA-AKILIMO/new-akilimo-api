<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('starch_prices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('starch_factory_id');
            $table->integer('price_class');
            $table->float('min_starch');
            $table->text('range_starch')->nullable();
            $table->double('price');
            $table->text('currency')->nullable();
            $table->timestamps();

            $table->unique(['starch_factory_id', 'price_class'], 'starch_factory_id_price_class_unique');
            // Foreign key reference
            $table->foreign('starch_factory_id')
                ->references('id')
                ->on('starch_factories')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('starch_prices');
    }
};
