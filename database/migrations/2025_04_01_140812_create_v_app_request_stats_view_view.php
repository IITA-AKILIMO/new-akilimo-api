<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    private string $viewName = 'v_app_request_stats_view';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $sql = <<<SQL
CREATE OR REPLACE VIEW $this->viewName AS SELECT
  app_report.id AS id,
  app_report.created_at AS request_date,
  app_report.device_token AS device_token,
  app_report.country_code AS country_code,
  app_report.lat AS lat,
  app_report.lon AS lon,
  ucase(`app_report`.`full_names`) AS full_names,
  app_report.gender AS gender_name,
  app_report.excluded AS excluded,
  CASE
    WHEN `app_report`.`gender` = 'Male' THEN
      'M'
    WHEN `app_report`.`gender` = 'Mwanaume' THEN
      'M'
    WHEN `app_report`.`gender` = 'Female' THEN
      'F'
    WHEN `app_report`.`gender` = 'Mwanamke' THEN
      'F'
    ELSE
      'NA'
  END AS gender,
  app_report.phone_number AS phone_number,
  ifnull(`user_feedback`.`user_type`, 'OTHER') AS user_type,
  CASE
    WHEN `app_report`.`fr` = 1 THEN
      'FR'
    WHEN `app_report`.`pp` = 1 THEN
      'PP'
    WHEN `app_report`.`ic` = 1 THEN
      'IC'
    WHEN `app_report`.`spp` = 1
      OR `app_report`.`sph` = 1 THEN
      'SPHS'
    ELSE
      'NA'
  END AS use_case,
  app_report.created_at AS created_at,
  app_report.updated_at AS updated_at
FROM
  (app_report);
SQL;

        DB::statement(query: $sql);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS {$this->viewName}");
    }
};
