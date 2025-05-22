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
        Schema::table('api_requests', function (Blueprint $table) {
            $table->jsonb('droid_request')->nullable(false)->default('{}')->change();

            $table->jsonb('plumber_request')->nullable(false)->default('{}')->change();

            $table->jsonb('plumber_response')->nullable(false)->default('{}')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        echo 'This migration is not reversible.';
    }
};
