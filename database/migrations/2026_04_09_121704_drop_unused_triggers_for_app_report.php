<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::unprepared('DROP TRIGGER IF EXISTS exclusion_flag_insert_trigger');
        DB::unprepared('DROP TRIGGER IF EXISTS exclusion_flag_update_trigger');
    }

    public function down(): void
    {
        throw new \RuntimeException('This migration is not reversible.');
    }
};
