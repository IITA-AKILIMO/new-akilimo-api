<?php

use App\Models\Base\OperationCost;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private string $table = 'operation_costs';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('operation_name', 100);
            $table->string('operation_type', 100);
            $table->char('country_code', 2);
            $table->decimal('min_cost', 20, 3);
            $table->decimal('max_cost', 20, 3);
            $table->boolean('is_active')->default(true);
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index(['operation_type', 'country_code'], 'idx-op-type-op-country');
        });


        $this->transferData();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->table);
    }

    private function transferData(): void
    {
        $records = DB::table('operation_costs_old')->get();
        $now = Carbon::now();
        $insertData = [];
        foreach ($records as $record) {
            /** @var OperationCost $record */

            $insertData[] = [
                'operation_name' => $record->operation_name,
                'operation_type' => $record->operation_type,
                'country_code' => 'TZ',
                'min_cost' => $record->min_tzs,
                'max_cost' => $record->max_tzs,
                'is_active' => $record->active,
                'created_at' => $record->created_at ?? $now,
                'updated_at' => $now,
            ];

            $insertData[] = [
                'operation_name' => $record->operation_name,
                'operation_type' => $record->operation_type,
                'country_code' => 'NG',
                'min_cost' => $record->min_ngn,
                'max_cost' => $record->max_ngn,
                'is_active' => $record->active,
                'created_at' => $record->created_at ?? $now,
                'updated_at' => $now,
            ];
        }

        DB::table('operation_costs')->truncate();
        DB::table('operation_costs')->insert($insertData);
    }
};
