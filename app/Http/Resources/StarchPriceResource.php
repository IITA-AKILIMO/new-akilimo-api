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
            'key' => $this->starch_factory->factory_name.$this->price_class,
            'starch_factory' => $this->starch_factory->factory_name,
            'starch_factory_label' => $this->starch_factory->factory_label,
            'class' => $this->price_class,
            'country' => $this->starch_factory->country,
            'min_starch' => $this->min_starch,
            'range_starch' => $this->range_starch,
            'price' => $this->price,
            'currency' => $this->currency,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
