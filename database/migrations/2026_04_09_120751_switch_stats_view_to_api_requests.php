<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Completes the consolidation started by improve_api_requests_table.
 *
 * That migration added stored generated columns (country_code, lat, lon,
 * full_names, phone_number, gender, fr/ic/pp/sph/spp) and indexes to
 * api_requests, making it self-contained.  The only missing piece was the
 * `excluded` flag, which was computed by the exclusion_flag_evaluation_proc
 * at write time and stored on app_report.
 *
 * This migration:
 *  1. Adds `excluded` as a stored generated column on api_requests, using the
 *     same logic as exclusion_flag_evaluation_proc (phone/name prefix checks).
 *     It can reference phone_number and full_names because those are already
 *     stored generated columns defined earlier in the table.
 *  2. Adds the missing created_at index and (created_at, excluded) composite
 *     index to api_requests — the improve migration omitted these.
 *  3. Rewrites v_app_request_stats_view to read from api_requests instead of
 *     app_report, making app_report and process_rec_request_proc redundant.
 *
 * After running this migration:
 *  - The dashboard reads entirely from api_requests (indexed stored columns,
 *    no JOIN, no stored procedure dependency).
 *  - app_report and both stored procedures can be retired in a future cleanup
 *    migration once confirmed stable.
 */
return new class extends Migration
{
    private string $viewName = 'v_app_request_stats_view';

    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('api_requests', function (Blueprint $table) {
                // Replicates exclusion_flag_evaluation_proc logic as a stored
                // generated column.  phone_number and full_names are already
                // stored generated columns, so MariaDB computes them first.
                $table->boolean('excluded')
                    ->storedAs("(CASE
                        WHEN phone_number LIKE '254%' OR phone_number LIKE '49%' THEN 1
                        WHEN full_names LIKE '%KREYE%' OR full_names LIKE '%C K%' THEN 1
                        ELSE 0
                    END)")
                    ->nullable()
                    ->after('spp');

                // The improve_api_requests migration omitted these — they are the
                // most critical for dashboard date-range query performance.
                $table->index('created_at', 'api_requests_created_at_idx');
                $table->index(['created_at', 'excluded'], 'api_requests_date_excluded_idx');
            });
        }

        // Rewrite view to api_requests.  All columns are now stored generated
        // (pre-computed, indexed) — no runtime JSON extraction, no JOIN.
        DB::statement("DROP VIEW IF EXISTS {$this->viewName}");

        $sql = <<<SQL
CREATE VIEW {$this->viewName} AS SELECT
  api_requests.id                   AS id,
  api_requests.created_at           AS request_date,
  api_requests.device_token         AS device_token,
  api_requests.country_code         AS country_code,
  api_requests.lat                  AS lat,
  api_requests.lon                  AS lon,
  UPPER(api_requests.full_names)    AS full_names,
  api_requests.gender               AS gender_name,
  api_requests.excluded             AS excluded,
  CASE
    WHEN api_requests.gender = 'Male'      THEN 'M'
    WHEN api_requests.gender = 'Mwanaume' THEN 'M'
    WHEN api_requests.gender = 'Female'   THEN 'F'
    WHEN api_requests.gender = 'Mwanamke' THEN 'F'
    ELSE 'NA'
  END                               AS gender,
  api_requests.phone_number         AS phone_number,
  'NA'                              AS user_type,
  CASE
    WHEN api_requests.fr  = 1                                    THEN 'FR'
    WHEN api_requests.pp  = 1                                    THEN 'PP'
    WHEN api_requests.ic  = 1                                    THEN 'IC'
    WHEN api_requests.spp = 1 OR api_requests.sph = 1           THEN 'SPHS'
    ELSE 'NA'
  END                               AS use_case,
  api_requests.created_at           AS created_at,
  api_requests.updated_at           AS updated_at
FROM api_requests
SQL;

        DB::statement($sql);
    }

    public function down(): void
    {
        throw new \RuntimeException('This migration is not reversible.');
    }
};
