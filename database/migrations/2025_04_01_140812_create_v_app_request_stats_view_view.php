<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("CREATE VIEW `v_app_request_stats_view` AS select `akilimo_new`.`app_report`.`id` AS `id`,`akilimo_new`.`app_report`.`created_at` AS `request_date`,`akilimo_new`.`app_report`.`device_token` AS `device_token`,`akilimo_new`.`app_report`.`country_code` AS `country_code`,`akilimo_new`.`app_report`.`lat` AS `lat`,`akilimo_new`.`app_report`.`lon` AS `lon`,ucase(`akilimo_new`.`app_report`.`full_names`) AS `full_names`,`akilimo_new`.`app_report`.`gender` AS `gender_name`,`akilimo_new`.`app_report`.`excluded` AS `excluded`,case when `akilimo_new`.`app_report`.`gender` = 'Male' then 'M' when `akilimo_new`.`app_report`.`gender` = 'Mwanaume' then 'M' when `akilimo_new`.`app_report`.`gender` = 'Female' then 'F' when `akilimo_new`.`app_report`.`gender` = 'Mwanamke' then 'F' else 'NA' end AS `gender`,`akilimo_new`.`app_report`.`phone_number` AS `phone_number`,ifnull(`akilimo_new`.`user_feedback`.`user_type`,'OTHER') AS `user_type`,case when `akilimo_new`.`app_report`.`fr` = 1 then 'FR' when `akilimo_new`.`app_report`.`pp` = 1 then 'PP' when `akilimo_new`.`app_report`.`ic` = 1 then 'IC' when `akilimo_new`.`app_report`.`spp` = 1 or `akilimo_new`.`app_report`.`sph` = 1 then 'SPHS' else 'NA' end AS `use_case`,`akilimo_new`.`app_report`.`created_at` AS `created_at`,`akilimo_new`.`app_report`.`updated_at` AS `updated_at` from (`akilimo_new`.`app_report` left join `akilimo_new`.`user_feedback` on(`akilimo_new`.`app_report`.`device_token` = `akilimo_new`.`user_feedback`.`device_token`))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS `v_app_request_stats_view`");
    }
};
