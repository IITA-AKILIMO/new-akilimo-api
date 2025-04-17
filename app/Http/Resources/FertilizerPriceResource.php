<?php

namespace App\Http\Resources;

use App\Models\FertilizerPrice;
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

        return [
            'id' => $fertilizerPrice->id,
            'fertilizer_key' => $fertilizerPrice->fertilizer_key,
            'country' => $fertilizerPrice->country,
            'sort_order' => $fertilizerPrice->sort_order,
            'min_price' => $fertilizerPrice->min_price,
            'max_price' => $fertilizerPrice->max_price,
            'price_per_bag' => $fertilizerPrice->price_per_bag,
            'active' => $fertilizerPrice->price_active,
            'description' => $fertilizerPrice->desc,
            'created_at' => $fertilizerPrice->created_at,
            'updated_at' => $fertilizerPrice->updated_at,
        ];
    }
}
