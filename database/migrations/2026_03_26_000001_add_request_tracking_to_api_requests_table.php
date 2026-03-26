<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('api_requests', function (Blueprint $table) {
            // #38 — server-generated correlation ID; original client identifier stored separately
            $table->string('device_token')->nullable()->after('request_id')->index();

            // #37 — latency tracking for Plumbr calls
            $table->timestamp('request_started_at')->nullable()->after('plumber_request');
            $table->unsignedInteger('request_duration_ms')->nullable()->after('request_started_at');
        });
    }

    public function down(): void
    {
        Schema::table('api_requests', function (Blueprint $table) {
            $table->dropIndex(['device_token']);
            $table->dropColumn(['device_token', 'request_started_at', 'request_duration_ms']);
        });
    }
};
