<?php

namespace App\Http\Resources;

use App\Http\Enums\EnumCountry;
use App\Models\Currency;
use App\Models\FertilizerPrice;
use App\Repositories\FertilizerPriceRepo;
use App\Utils\CurrencyConversion;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FertilizerPriceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var FertilizerPrice $fertilizerPrice */
        $fertilizerPrice = $this->resource;

        $repo = new FertilizerPriceRepo();
        $conv = new CurrencyConversion();
        $currencyCode = EnumCountry::fromCode($fertilizerPrice->country)->currency();
        $currency = Currency::whereCurrencyCode($currencyCode)->first();


        $minPrice = $repo->findBySortOrderAndFertilizerKey(1, $fertilizerPrice->fertilizer_key);
        $maxPrice = $repo->findBySortOrderAndFertilizerKey(4, $fertilizerPrice->fertilizer_key);

        $priceRange = $conv->convertPriceToLocalCurrency(
            minUsd: $fertilizerPrice->price_per_bag,
            maxUsd: $fertilizerPrice->price_per_bag,
            currencyRate: 1,
            nearestValue: 1000.0,
            currency: $currency
        );


        return [
            'id' => $fertilizerPrice->id,
            'price_id' => $fertilizerPrice->id,
            'fertilizer_key' => $fertilizerPrice->fertilizer_key,
            'fertilizer_country' => "{$fertilizerPrice->country}$fertilizerPrice->id",
            'country_code' => $fertilizerPrice->country,
            'currency'=>$currency,
            'sort_order' => $fertilizerPrice->sort_order,
            'min_local_price' => $fertilizerPrice->min_price,
            'max_local_price' => $fertilizerPrice->max_price,
            'min_allowed_price' => $minPrice->min_price,
            'max_allowed_price' => $maxPrice->max_price,
            'price_per_bag' => $fertilizerPrice->price_per_bag,
            'price_range' => $priceRange,
            'active' => $fertilizerPrice->price_active,
            'description' => $fertilizerPrice->desc,
            'created_at' => $fertilizerPrice->created_at,
            'updated_at' => $fertilizerPrice->updated_at,
        ];
    }
}
