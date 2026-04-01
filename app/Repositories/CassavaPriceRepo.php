<?php

namespace App\Repositories;

use App\Data\MinMaxPriceDto;
use App\Models\CassavaPrice;

class CassavaPriceRepo extends BaseRepo
{
    protected function model(): string
    {
        return CassavaPrice::class;
    }

    /**
     * Retrieves the minimum and maximum price entity for a given country code.
     */
    public function findPriceBandsByCountryCode(string $countryCode): MinMaxPriceDto
    {
        $result = CassavaPrice::query()
            ->where('country', $countryCode)
            ->where('min_local_price', '>', 0)
            ->selectRaw('MIN(min_local_price) as min_price, MAX(max_local_price) as max_price')
            ->first();

        return new MinMaxPriceDto(
            minLocalPrice: (float) ($result->min_price ?? 0),
            maxLocalPrice: (float) ($result->max_price ?? 0)
        );
    }

    /**
     * Load price bands for all countries in one query.
     * Returns an array keyed by country code.
     *
     * @return array<string, MinMaxPriceDto>
     */
    public function findAllPriceBands(): array
    {
        $results = CassavaPrice::query()
            ->selectRaw('country, MIN(NULLIF(min_local_price, 0)) as min_price, MAX(max_local_price) as max_price')
            ->groupBy('country')
            ->get();

        $map = [];
        foreach ($results as $row) {
            $map[$row->country] = new MinMaxPriceDto(
                minLocalPrice: (float) ($row->min_price ?? 0),
                maxLocalPrice: (float) ($row->max_price ?? 0)
            );
        }

        return $map;
    }
}
