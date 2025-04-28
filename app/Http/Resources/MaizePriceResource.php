<?php

namespace App\Http\Resources;

use App\Models\MaizePrice;
use App\Repositories\MaizePriceRepo;

class MaizePriceResource extends \Illuminate\Http\Resources\Json\JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @var MaizePrice $this ->resource
     */
    public function toArray($request): array
    {
        /** @var MaizePrice $model */
        $model = $this->resource;

        $repo = new MaizePriceRepo();
        $price = $repo->findPriceBandsByCountryCode($model->country, $model->produce_type);

        $avgPrice = ($model->min_local_price + $model->max_local_price) / 2;
        $tag = "{$model->id}";
        if ($avgPrice === -1.0) {
            $tag = 'exact';
        }

        return [
            'id' => $model->id,
            'country_code' => $model->country,
            'produce_type' => $model->produce_type,
            'min_local_price' => $model->min_local_price,
            'max_local_price' => $model->max_local_price,
            'average_price' => $avgPrice,
            'exact_price' => $avgPrice === -1.0,
            'item_tag' => $tag,
            'min_allowed_price' => $price->min_price,
            'max_allowed_price' => $price->max_price,
            'active' => $model->price_active,
            'sort_order' => $model->sort_order,
        ];
    }
}
