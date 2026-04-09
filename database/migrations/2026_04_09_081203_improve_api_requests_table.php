<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('api_requests', function (Blueprint $table) {
            // Add STORED generated columns
            $table->string('country_code', 5)
                ->storedAs("JSON_UNQUOTE(JSON_EXTRACT(plumber_request, '$.country'))")
                ->nullable()
                ->after('device_token');

            $table->decimal('lat', 10, 6)
                ->storedAs("JSON_UNQUOTE(JSON_EXTRACT(plumber_request, '$.lat'))")
                ->nullable()
                ->after('country_code');

            $table->decimal('lon', 10, 6)
                ->storedAs("JSON_UNQUOTE(JSON_EXTRACT(plumber_request, '$.lon'))")
                ->nullable()
                ->after('lat');

            $table->string('full_names', 255)
                ->storedAs("JSON_UNQUOTE(JSON_EXTRACT(droid_request, '$.userInfo.userName'))")
                ->nullable()
                ->after('lon');

            $table->string('phone_number', 50)
                ->storedAs("JSON_UNQUOTE(JSON_EXTRACT(droid_request, '$.userInfo.mobileNumber'))")
                ->nullable()
                ->after('full_names');

            $table->string('gender', 50)
                ->storedAs("JSON_UNQUOTE(JSON_EXTRACT(droid_request, '$.userInfo.gender'))")
                ->nullable()
                ->after('phone_number');

            // Boolean flags
            $table->boolean('fr')
                ->storedAs("(JSON_UNQUOTE(JSON_EXTRACT(plumber_request, '$.FR')) = 'true')")
                ->nullable()
                ->after('gender');

            $table->boolean('ic')
                ->storedAs("(JSON_UNQUOTE(JSON_EXTRACT(plumber_request, '$.IC')) = 'true')")
                ->nullable()
                ->after('fr');

            $table->boolean('pp')
                ->storedAs("(JSON_UNQUOTE(JSON_EXTRACT(plumber_request, '$.PP')) = 'true')")
                ->nullable()
                ->after('ic');

            $table->boolean('sph')
                ->storedAs("(JSON_UNQUOTE(JSON_EXTRACT(plumber_request, '$.SPH')) = 'true')")
                ->nullable()
                ->after('pp');

            $table->boolean('spp')
                ->storedAs("(JSON_UNQUOTE(JSON_EXTRACT(plumber_request, '$.SPP')) = 'true')")
                ->nullable()
                ->after('sph');

            // Indexes for performance
            $table->index('country_code');
            $table->index(['lat', 'lon']);
            $table->index('full_names');
            $table->index('phone_number');
            $table->index('gender');
            $table->index(['fr', 'ic', 'pp', 'sph', 'spp']);
        });

        // No backfill needed — STORED columns compute automatically
    }

    public function down(): void
    {
        Schema::table('api_requests', function (Blueprint $table) {
            $table->dropIndex(['country_code']);
            $table->dropIndex(['lat', 'lon']);
            $table->dropIndex(['full_names']);
            $table->dropIndex(['phone_number']);
            $table->dropIndex(['gender']);
            $table->dropIndex(['fr', 'ic', 'pp', 'sph', 'spp']);
            $table->dropColumn([
                'country_code', 'lat', 'lon', 'full_names',
                'phone_number', 'gender', 'fr', 'ic', 'pp', 'sph', 'spp',
            ]);
        });
    }
};
