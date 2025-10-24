<?php

namespace App\Repositories;

use App\Data\MinMaxPriceDto;
use App\Models\CassavaPrice;

class CassavaPriceRepo
{
    /**
     * Retrieves the minimum and maximum price entity for a given country code.
     *
     * @param string $countryCode The country code used to filter the prices.
     * @return MinMaxPriceDto Contains the minimum and maximum local prices for the specified country.
     */
    public function findPriceBandsByCountryCode(string $countryCode): MinMaxPriceDto
    {
        $minPrice = CassavaPrice::whereCountry($countryCode)
            ->where('min_local_price', '>', 0)
            ->min('min_local_price');

        $maxPrice = CassavaPrice::whereCountry($countryCode)->max('max_local_price');

        return new MinMaxPriceDto(minLocalPrice: (float)$minPrice, maxLocalPrice: (float)$maxPrice);
    }
}
