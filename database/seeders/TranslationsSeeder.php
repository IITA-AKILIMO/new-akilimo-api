<?php

namespace Database\Seeders;

use App\Models\StarchFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class TranslationsSeeder extends Seeder
{
    /**
     * @return void
     * @throws \League\Csv\Exception
     * @throws \League\Csv\UnavailableStream
     */
    public function run(): void
    {
        // Use fromPath instead of createFromPath
        $csv = Reader::from(database_path('seeders/data/translations.csv'), 'r');
        $csv->setHeaderOffset(0);

        $batch = [];
        $batchSize = 1000;

        foreach ($csv as $record) {
            $batch[] = [
                'key' => $record['key'],
                'en' => $record['en'],
                'sw' => $record['sw'] ?? null,
                'rw' => $record['rw'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($batch) >= $batchSize) {
                DB::table('translations')->upsert(
                    $batch,
                    ['key'],
                    ['en', 'sw', 'rw', 'updated_at']
                );
                $batch = [];
            }
        }

        if (!empty($batch)) {
            DB::table('translations')->upsert(
                $batch,
                ['key'],
                ['en', 'sw', 'rw', 'updated_at']
            );

        }
    }
}
