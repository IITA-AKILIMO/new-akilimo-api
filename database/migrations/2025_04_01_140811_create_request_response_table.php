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
        Schema::create('request_response', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('request_id', 200);
            $table->json('droid_request')->default('{}');
            $table->json('plumber_request')->default('{}');
            $table->json('plumber_response')->default('{}');
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->dateTime('updated_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_response');
    }
};
