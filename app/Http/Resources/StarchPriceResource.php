<?php

namespace App\Http\Resources;

use App\Models\StarchPrice;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin StarchPrice
 **/
class StarchPriceResource extends JsonResource
{
    public static $wrap = null;

    public function toArray($request): array
    {

        return [
            /** Composite key of factory name and price class */
            'key' => $this->starch_factory->factory_name.$this->price_class,
            /** Starch factory name */
            'starch_factory' => $this->starch_factory->factory_name,
            /** Starch factory label */
            'starch_factory_label' => $this->starch_factory->factory_label,
            /** Price class/grade */
            'class' => $this->price_class,
            /** ISO 3166-1 alpha-2 country code */
            'country' => $this->starch_factory->country,
            /** Minimum starch content */
            'min_starch' => $this->min_starch,
            /** Starch content range */
            'range_starch' => $this->range_starch,
            /** Price value */
            'price' => $this->price,
            /** ISO 4217 currency code */
            'currency' => $this->currency,
            /** Timestamp when created */
            'created_at' => $this->created_at?->toDateTimeString(),
            /** Timestamp when last updated */
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
