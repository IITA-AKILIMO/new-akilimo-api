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
        Schema::create('user_feedback', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->mediumText('akilimo_usage');
            $table->string('user_type', 25)->default('OTHER');
            $table->integer('akilimo_rec_rating');
            $table->integer('akilimo_useful_rating');
            $table->string('language', 5)->nullable();
            $table->mediumText('device_token')->nullable();
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->dateTime('updated_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_feedback');
    }
};
