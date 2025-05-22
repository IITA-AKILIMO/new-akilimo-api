<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('starch_factory', 'starch_factories');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('starch_factories', 'starch_factory');
    }
};
