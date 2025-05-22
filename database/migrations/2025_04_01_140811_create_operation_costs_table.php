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
        Schema::create('operation_costs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('operation_name');
            $table->string('operation_type');
            $table->decimal('min_ngn', 12, 3);
            $table->decimal('max_ngn', 12, 3);
            $table->decimal('min_tzs', 12, 3);
            $table->decimal('max_tzs', 12, 3);
            $table->boolean('active')->default(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operation_costs');
    }
};
