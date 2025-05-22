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
        Schema::create('app_report', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('device_token')->nullable()->index('device_token_idx');
            $table->string('country_code', 4)->nullable();
            $table->decimal('lat', 10, 6)->nullable();
            $table->decimal('lon', 10, 6)->nullable();
            $table->string('full_names', 150)->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('gender', 20)->nullable();
            $table->boolean('excluded')->nullable()->default(false);
            $table->string('user_type', 20)->nullable();
            $table->boolean('fr')->nullable()->default(false);
            $table->boolean('ic')->nullable()->default(false);
            $table->boolean('pp')->nullable()->default(false);
            $table->boolean('spp')->nullable()->default(false);
            $table->boolean('sph')->nullable()->default(false);
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->dateTime('updated_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_report');
    }
};
