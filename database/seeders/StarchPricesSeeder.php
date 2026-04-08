<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use League\Csv\Reader;
use Illuminate\Support\Facades\DB;
use App\Models\StarchFactory;

class StarchPricesSeeder extends Seeder
{
    /**
     * @return void
     * @throws \League\Csv\Exception
     * @throws \League\Csv\UnavailableStream
     */
    public function run(): void
    {
        // Use fromPath instead of createFromPath
        $csv = Reader::from(database_path('seeders/data/starch_prices.csv'), 'r');
        $csv->setHeaderOffset(0);

        $batch = [];
        $batchSize = 1000;

        foreach ($csv as $record) {
            $factory = StarchFactory::firstOrCreate(
                ['factory_name' => $record['starchFactory']],
                [
                    'factory_label' => $record['starchFactory_label'] ?? '',
                    'country' => $record['country'] ?? '',
                ]
            );

            $batch[] = [
                'starch_factory_id' => $factory->id,
                'price_class' => (int)$record['class'],
                'min_starch' => (float)$record['minStarch'],
                'range_starch' => $record['rangeStarch'] ?? '',
                'price' => (float)$record['price'],
                'created_at' => now(),
                'updated_at' => now(),
            ];


            if (count($batch) >= $batchSize) {
                DB::table('starch_prices')->upsert(
                    $batch,
                    ['starch_factory_id', 'price_class'],
                    ['min_starch', 'range_starch', 'price', 'updated_at']
                );
                $batch = [];
            }
        }

        if (!empty($batch)) {
            DB::table('starch_prices')->upsert(
                $batch,
                ['starch_factory_id', 'price_class'],
                ['min_starch', 'range_starch', 'price', 'updated_at']
            );
        }
    }
}
