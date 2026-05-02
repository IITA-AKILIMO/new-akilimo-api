<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Retires the trigger + stored procedures that kept app_report in sync with
 * api_requests.
 *
 * after_request_insert trigger — fired on every INSERT into api_requests and
 *   called process_rec_request to copy extracted fields into app_report.
 *   Must be dropped BEFORE the procedure, otherwise MariaDB will error on
 *   every new api_requests INSERT (trigger references a missing procedure).
 *
 * process_rec_request — manually extracted JSON fields from the request blobs
 *   and wrote them to app_report.  api_requests now has stored generated
 *   columns (country_code, lat, lon, full_names, phone_number, gender,
 *   fr/ic/pp/sph/spp, excluded) that do this automatically.
 *
 * exclusion_flag_evaluation — computed the excluded flag from phone/name
 *   prefix rules.  The same logic is now in api_requests.excluded as a stored
 *   generated column.
 *
 * app_report itself is left intact — it holds historical data and can be
 * archived or dropped in a later cleanup once confirmed no longer needed.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        // Drop trigger first — it references process_rec_request.
        // Dropping the procedure while the trigger exists would cause every
        // subsequent api_requests INSERT to fail with "PROCEDURE does not exist".
        DB::unprepared('DROP TRIGGER IF EXISTS after_request_insert');

        DB::unprepared('DROP PROCEDURE IF EXISTS process_rec_request');
        DB::unprepared('DROP PROCEDURE IF EXISTS exclusion_flag_evaluation');
    }

    public function down(): void
    {
        throw new \RuntimeException('This migration is not reversible.');
    }
};
