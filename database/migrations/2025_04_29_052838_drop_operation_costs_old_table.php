<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('operation_costs_old');
    }

    public function down(): void
    {
        // Optional: Recreate it if needed
        if (!Schema::hasTable('operation_costs_old')) {
            Schema::create('operation_costs_old', function (Blueprint $table) {
                $table->id();
                $table->string('operation_name');
                $table->string('operation_type');
                $table->decimal('min_ngn', 12, 3);
                $table->decimal('max_ngn', 12, 3);
                $table->decimal('min_tzs', 12, 3);
                $table->decimal('max_tzs', 12, 3);
                $table->boolean('active')->default(true);
                $table->timestampTz('created_at')->useCurrent();
                $table->timestampTz('updated_at')->useCurrent()->useCurrentOnUpdate();
            });
        }

        $this->restoreNgCosts();
        $this->updateTzCosts();
    }

    /**
     * Restore NG operation costs into the old table.
     */
    private function restoreNgCosts(): void
    {
        $now = Carbon::now();

        $ngCosts = DB::table('operation_costs')
            ->where('country_code', 'NG')
            ->orderByDesc('min_cost')
            ->get();

        $restored = $ngCosts->map(function ($record) use ($now) {
            return [
                'operation_name' => $record->operation_name,
                'operation_type' => $record->operation_type,
                'min_ngn' => $record->min_cost,
                'max_ngn' => $record->max_cost,
                'min_tzs' => 0,
                'max_tzs' => 0,
                'active' => $record->is_active,
                'created_at' => $record->created_at ?? $now,
                'updated_at' => $now,
            ];
        })->toArray();

        DB::table('operation_costs_old')->insert($restored);
        echo("✅ Inserted " . count($restored) . " NG records.\n\n");
    }

    /**
     * Update TZS operation costs in the old table based on matching NG entries.
     */
    private function updateTzCosts(): void
    {
        $tzRecords = DB::table('operation_costs')
            ->where('country_code', 'TZ')
            ->select('operation_name', 'operation_type', 'min_cost', 'max_cost')
            ->orderByDesc('min_cost')
            ->get();

        $updated = 0;

        foreach ($tzRecords as $tz) {
            $affected = DB::update("
                UPDATE operation_costs_old
                SET min_tzs = ?, max_tzs = ?, updated_at = ?
                WHERE id = (
                    SELECT id FROM (
                        SELECT id FROM operation_costs_old
                        WHERE operation_name = ?
                        AND operation_type = ?
                        AND min_tzs = 0
                        AND max_tzs = 0
                        ORDER BY min_ngn DESC
                        LIMIT 1
                    ) AS temp
                )
            ", [
                $tz->min_cost,
                $tz->max_cost,
                Carbon::now(),
                $tz->operation_name,
                $tz->operation_type,
            ]);

            if ($affected) {
                $updated++;
            }
        }

        echo("✅ Updated TZS values for $updated records.");
    }
};
