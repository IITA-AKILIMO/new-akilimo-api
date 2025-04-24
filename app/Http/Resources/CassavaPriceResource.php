<?php

namespace App\Http\Resources;

use App\Models\CassavaPrice;
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

        $avgPrice = ($cassavaPrice->min_local_price + $cassavaPrice->max_local_price) / 2;

        return [
            'id' => $cassavaPrice->id,
            'country_code' => $cassavaPrice->country,
            'min_local_price' => $cassavaPrice->min_local_price,
            'max_local_price' => $cassavaPrice->max_local_price,
            'average_price' => $avgPrice,
            'min_allowed_price' => $price->min_price,
            'max_allowed_price' => $price->max_price,
            'active' => $cassavaPrice->price_active,
        ];
    }
}
