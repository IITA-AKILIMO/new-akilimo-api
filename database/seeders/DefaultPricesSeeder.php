<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Exception;
use League\Csv\Reader;
use League\Csv\UnavailableStream;

class DefaultPricesSeeder extends Seeder
{
    /**
     * @throws Exception
     * @throws UnavailableStream
     */
    public function run(): void
    {
        // Use fromPath instead of createFromPath
        $csv = Reader::from(database_path('seeders/data/default_prices.csv'), 'r');
        $csv->setHeaderOffset(0);

        $batch = [];
        $batchSize = 1000;

        foreach ($csv as $record) {
            $batch[] = [
                'country' => $record['Country'],
                'item' => $record['Item'],
                'price' => $record['Price'],

                'currency' => $record['currency'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($batch) >= $batchSize) {
                DB::table('default_prices')->upsert(
                    $batch,
                    ['country', 'item'],
                    ['currency', 'price', 'updated_at']
                );
                $batch = [];
            }
        }

        if (! empty($batch)) {
            DB::table('default_prices')->upsert(
                $batch,
                ['country', 'item'],
                ['currency', 'price', 'updated_at']
            );

        }
    }
}
