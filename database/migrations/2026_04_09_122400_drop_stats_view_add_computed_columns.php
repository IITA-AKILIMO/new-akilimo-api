<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;  // used in up() only

/**
 * Finalises the move away from v_app_request_stats_view.
 *
 * The view became a thin alias over api_requests after migration 130000
 * switched its source table.  The only runtime expressions it provided were:
 *
 *   use_case  — CASE on fr/ic/pp/sph/spp flags (already stored generated)
 *   gender    — M/F/NA short-code from the raw gender string
 *   full_names uppercase — UPPER() wrapper
 *
 * Adding these as stored generated columns on api_requests makes the view
 * redundant and lets the repo query the table directly — one fewer layer of
 * indirection, and the planner can use indexes on the generated columns.
 */
return new class extends Migration
{
    private string $viewName = 'v_app_request_stats_view';

    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('api_requests', function (Blueprint $table) {
                $table->string('use_case', 10)
                    ->storedAs("(CASE
                        WHEN fr  = 1             THEN 'FR'
                        WHEN pp  = 1             THEN 'PP'
                        WHEN ic  = 1             THEN 'IC'
                        WHEN spp = 1 OR sph = 1  THEN 'SPHS'
                        ELSE 'NA'
                    END)")
                    ->nullable()
                    ->after('excluded');

                $table->string('gender_code', 2)
                    ->storedAs("(CASE
                        WHEN gender IN ('Male',   'Mwanaume') THEN 'M'
                        WHEN gender IN ('Female', 'Mwanamke') THEN 'F'
                        ELSE 'NA'
                    END)")
                    ->nullable()
                    ->after('use_case');

                $table->index('use_case', 'api_requests_use_case_idx');
                $table->index('gender_code', 'api_requests_gender_code_idx');
            });
        }

        DB::statement("DROP VIEW IF EXISTS {$this->viewName}");
    }

    public function down(): void
    {
        throw new \RuntimeException('This migration is not reversible.');
    }
};
