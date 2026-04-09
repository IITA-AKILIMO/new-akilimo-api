<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountriesSeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['code' => 'ET', 'name' => 'Ethiopia',     'sort_order' => 1],
            ['code' => 'GH', 'name' => 'Ghana',        'sort_order' => 2],
            ['code' => 'KE', 'name' => 'Kenya',        'sort_order' => 3],
            ['code' => 'MZ', 'name' => 'Mozambique',   'sort_order' => 4],
            ['code' => 'NG', 'name' => 'Nigeria',      'sort_order' => 5],
            ['code' => 'RW', 'name' => 'Rwanda',       'sort_order' => 6],
            ['code' => 'TZ', 'name' => 'Tanzania',     'sort_order' => 7],
            ['code' => 'UG', 'name' => 'Uganda',       'sort_order' => 8],
        ];

        foreach ($countries as $country) {
            Country::updateOrCreate(
                ['code' => $country['code']],
                ['name' => $country['name'], 'active' => true, 'sort_order' => $country['sort_order']],
            );
        }
    }
}
