<?php

namespace App\Http\Resources;

use App\Models\Currency;
use App\Models\FertilizerPrice;
use App\Utils\CurrencyConversion;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FertilizerPriceResource extends JsonResource
{
    public function __construct(
        $resource,
        private readonly ?FertilizerPrice $minBand,
        private readonly ?FertilizerPrice $maxBand,
        private readonly ?Currency $currency,
    ) {
        parent::__construct($resource);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var FertilizerPrice $fertilizerPrice */
        $fertilizerPrice  = $this->resource;
        $conv             = new CurrencyConversion();
        $currencyResource = $this->currency ? CurrencyResource::make($this->currency) : null;

        $priceRange = $conv->convertPriceToLocalCurrency(
            minUsd: $fertilizerPrice->price_per_bag,
            maxUsd: $fertilizerPrice->price_per_bag,
            currencyRate: 1,
            nearestValue: 1000.0,
            currency: $this->currency
        );

        return [
            'id'                 => $fertilizerPrice->id,
            'price_id'           => $fertilizerPrice->id,
            'fertilizer_key'     => $fertilizerPrice->fertilizer_key,
            'fertilizer_country' => "{$fertilizerPrice->country}{$fertilizerPrice->id}",
            'country_code'       => $fertilizerPrice->country,
            'currency'           => $currencyResource,
            'sort_order'         => $fertilizerPrice->sort_order,
            'min_local_price'    => $fertilizerPrice->min_price,
            'max_local_price'    => $fertilizerPrice->max_price,
            'min_allowed_price'  => $this->minBand?->min_price,
            'max_allowed_price'  => $this->maxBand?->max_price,
            'price_per_bag'      => $fertilizerPrice->price_per_bag,
            'price_range'        => $priceRange,
            'active'             => $fertilizerPrice->price_active,
            'description'        => $fertilizerPrice->desc,
            'created_at'         => $fertilizerPrice->created_at,
            'updated_at'         => $fertilizerPrice->updated_at,
        ];
    }
}
