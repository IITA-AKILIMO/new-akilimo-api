<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProducePricesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('produce_prices')->truncate();

        // 1. Maize prices
        $maize = DB::table('maize_prices')->select('country', 'min_local_price', 'max_local_price', 'price_active', 'sort_order', 'created_at')->get();
        foreach ($maize as $row) {
            DB::table('produce_prices')->insert([
                'country' => $row->country,
                'produce_name' => 'maize',
                'min_price' => $row->min_local_price,
                'max_price' => $row->max_local_price,
                'is_active' => $row->price_active,
                'sort_order' => $row->sort_order,
                'created_at' => $row->created_at,
                'updated_at' => now(),
            ]);
        }

        // 2. Potato prices
        $potato = DB::table('potato_prices')->select('country', 'min_local_price', 'max_local_price', 'price_active', 'sort_order', 'created_at')->get();
        foreach ($potato as $row) {
            DB::table('produce_prices')->insert([
                'country' => $row->country,
                'produce_name' => 'potato',
                'min_price' => $row->min_local_price,
                'max_price' => $row->max_local_price,
                'is_active' => $row->price_active,
                'sort_order' => $row->sort_order,
                'created_at' => $row->created_at,
                'updated_at' => now(),
            ]);
        }

        // 3. Cassava prices
        $cassava = DB::table('cassava_prices')->select('country', 'min_local_price', 'max_local_price', 'price_active', 'sort_order', 'created_at')->get();
        foreach ($cassava as $row) {
            DB::table('produce_prices')->insert([
                'country' => $row->country,
                'produce_name' => 'cassava',
                'min_price' => $row->min_local_price,
                'max_price' => $row->max_local_price,
                'is_active' => $row->price_active,
                'sort_order' => $row->sort_order,
                'created_at' => $row->created_at,
                'updated_at' => now(),
            ]);
        }
    }
}
