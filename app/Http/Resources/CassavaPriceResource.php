<?php

namespace App\Http\Resources;

use App\Http\Enums\EnumCountry;
use App\Models\CassavaPrice;
use App\Models\Currency;
use App\Repositories\CassavaPriceRepo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CassavaPriceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var CassavaPrice $cassavaPrice */
        $cassavaPrice = $this->resource;
        $repo = new CassavaPriceRepo;
        $price = $repo->findPriceBandsByCountryCode($cassavaPrice->country);

        $currencyCode = EnumCountry::fromCode($cassavaPrice->country)->currency();
        $currency = Currency::whereCurrencyCode($currencyCode)->first();
        $currencyResource = CurrencyResource::make($currency);

        $avgPrice = ($cassavaPrice->min_local_price + $cassavaPrice->max_local_price) / 2;
        $tag = "$cassavaPrice->id";
        if ($avgPrice === -1.0) {
            $tag = 'exact';
        }

        return [
            'id' => $cassavaPrice->id,
            'country_code' => $cassavaPrice->country,
            'min_local_price' => $cassavaPrice->min_local_price,
            'max_local_price' => $cassavaPrice->max_local_price,
            'currency' => $currencyResource,
            'average_price' => $avgPrice,
            'exact_price' => $avgPrice === -1.0,
            'item_tag' => $tag,
            'min_allowed_price' => $price->minPrice,
            'max_allowed_price' => $price->maxPrice,
            'active' => $cassavaPrice->price_active,
            'sort_order' => $cassavaPrice->sort_order,
        ];
    }
}
