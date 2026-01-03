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
        Schema::create('cassava_units', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('unit_weight');
            $table->string('label', 50);
            $table->float('sort_order')->default(0)->comment('Sort order for display');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent()->useCurrentOnUpdate();
        });

        DB::table('cassava_units')->insert([
            ['unit_weight' => 1, 'sort_order' => 1, 'label' => 'ONE_KG', 'description' => 'Small-scale sale unit'],
            ['unit_weight' => 50, 'sort_order' => 2, 'label' => 'FIFTY_KG', 'description' => 'Small-scale sale unit'],
            ['unit_weight' => 100, 'sort_order' => 3, 'label' => 'HUNDRED_KG', 'description' => 'Medium unit for farmers'],
            ['unit_weight' => 1000, 'sort_order' => 6, 'label' => 'THOUSAND_KG', 'description' => 'Full ton for industrial sale'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cassava_units');
    }
};
