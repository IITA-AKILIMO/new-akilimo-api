<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->bigIncrements('id')->primary();
            $table->string('key', 50)->unique();
            $table->text('en')->comment('base language');
            $table->text('sw')->nullable();
            $table->text('rw')->nullable();
            $table->timestamps(); // adds created_at and updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
