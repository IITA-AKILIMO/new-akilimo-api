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
        DB::unprepared("CREATE DEFINER=`akilimo`@`%` PROCEDURE `exclusion_flag_evaluation`(IN phone_number text, IN full_names text, out excluded TINYINT)
BEGIN

    IF (phone_number like '254%' OR phone_number like '49%') THEN
        SET excluded = 1;
    ELSEIF (full_names like '%KREYE%' OR full_names like '%C K%') THEN
        SET excluded = 1;
    ELSE
        SET excluded = 0;
    END IF;

END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS exclusion_flag_evaluation");
    }
};
