<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('countries');

        Schema::create('countries', function (Blueprint $table) {
            $table->id();

            // ISO codes
            $table->string('code', 2)->unique()->comment('ISO 3166-1 alpha-2 country code');
            $table->string('name', 100)->index()->comment('Country name');

            // Status & ordering
            $table->boolean('active')->default(true)->index();
            $table->unsignedSmallInteger('sort_order')->nullable();

            // Centroid coordinates
            $table->decimal('latitude', 10, 8)->nullable()->comment('Country centroid latitude');
            $table->decimal('longitude', 11, 8)->nullable()->comment('Country centroid longitude');

            // Bounding box (fast spatial filtering)
            $table->decimal('min_latitude', 10, 8)->nullable();
            $table->decimal('max_latitude', 10, 8)->nullable();
            $table->decimal('min_longitude', 11, 8)->nullable();
            $table->decimal('max_longitude', 11, 8)->nullable();

            // Full geometry (MariaDB spatial type)
            $table->geometry('boundary')
                ->default(DB::raw("ST_GeomFromText('GEOMETRYCOLLECTION EMPTY')"))
                ->comment('Country boundary polygon or multipolygon');

            $table->timestamps();

            // Indexes
            $table->index(['latitude', 'longitude'], 'idx_country_coordinates');
            $table->index(['min_latitude', 'max_latitude', 'min_longitude', 'max_longitude'], 'idx_country_bbox');
            $table->spatialIndex('boundary', 'idx_country_boundary');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
