<?php

namespace App\Http\Resources;

use App\Models\PotatoPrice;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PotatoPriceResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var PotatoPrice $model */
        $model    = $this->resource;
        $avgPrice = ($model->min_local_price + $model->max_local_price) / 2;
        $tag      = "{$model->id}";
        if ($avgPrice === -1.0) {
            $tag = 'exact';
        }

        return [
            'id'                => $model->id,
            'country_code'      => $model->country,
            'min_local_price'   => $model->min_local_price,
            'max_local_price'   => $model->max_local_price,
            'average_price'     => $avgPrice,
            'exact_price'       => $avgPrice === -1.0,
            'item_tag'          => $tag,
            'min_allowed_price' => $model->min_price,
            'max_allowed_price' => $model->max_price,
            'active'            => $model->price_active,
            'sort_order'        => $model->sort_order,
        ];
    }
}
