<?php

namespace App\Http\Resources;

use App\Data\MinMaxPriceDto;
use App\Models\CassavaPrice;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CassavaPriceResource extends JsonResource
{
    public function __construct(
        $resource,
        private readonly MinMaxPriceDto $priceBand,
        private readonly ?Currency $currency,
    ) {
        parent::__construct($resource);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var CassavaPrice $cassavaPrice */
        $cassavaPrice    = $this->resource;
        $currencyResource = $this->currency ? CurrencyResource::make($this->currency) : null;
        $avgPrice        = ($cassavaPrice->min_local_price + $cassavaPrice->max_local_price) / 2;
        $tag             = "$cassavaPrice->id";
        if ($avgPrice === -1.0) {
            $tag = 'exact';
        }

        return [
            'id'                => $cassavaPrice->id,
            'country_code'      => $cassavaPrice->country,
            'min_local_price'   => $cassavaPrice->min_local_price,
            'max_local_price'   => $cassavaPrice->max_local_price,
            'currency'          => $currencyResource,
            'average_price'     => $avgPrice,
            'exact_price'       => $avgPrice === -1.0,
            'item_tag'          => $tag,
            'min_allowed_price' => $this->priceBand->minPrice,
            'max_allowed_price' => $this->priceBand->maxPrice,
            'active'            => $cassavaPrice->price_active,
            'sort_order'        => $cassavaPrice->sort_order,
        ];
    }
}
