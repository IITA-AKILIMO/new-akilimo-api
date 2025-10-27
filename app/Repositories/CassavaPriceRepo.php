<?php

namespace App\Repositories;

use App\Data\MinMaxPriceDto;
use App\Models\ApiRequest;
use App\Models\CassavaPrice;

class CassavaPriceRepo extends BaseRepo
{
    /**
     * @return class-string<ApiRequest>
     */
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
}
