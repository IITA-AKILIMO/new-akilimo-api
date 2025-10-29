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
        Schema::create('cassava_units', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('unit_weight');
            $table->string('label', 50);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent()->useCurrentOnUpdate();
        });

        DB::table('cassava_units')->insert([
            ['unit_weight' => 1, 'label' => '1 KG Bag', 'description' => 'Small-scale sale unit'],
            ['unit_weight' => 50, 'label' => '50 KG Bag', 'description' => 'Small-scale sale unit'],
            ['unit_weight' => 100, 'label' => '100 KG Bag', 'description' => 'Medium unit for farmers'],
            ['unit_weight' => 250, 'label' => '250 KG Load', 'description' => 'Quarter-ton unit'],
            ['unit_weight' => 500, 'label' => '500 KG Load', 'description' => 'Half-ton batch'],
            ['unit_weight' => 1000, 'label' => '1 Tonne', 'description' => 'Full ton for industrial sale'],
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
