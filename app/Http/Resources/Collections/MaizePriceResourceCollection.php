<?php

namespace App\Http\Resources\Collections;

use App\Data\MinMaxPriceDto;
use App\Http\Resources\MaizePriceResource;
use App\Repositories\MaizePriceRepo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MaizePriceResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * Price bands are loaded once for all (country, produce_type) combinations on this
     * page, eliminating the N+1 query that the old per-row repo instantiation caused.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var array<string, MinMaxPriceDto> $priceBands */
        $priceBands = app(MaizePriceRepo::class)->findPriceBandsBulk();

        return [
            'data' => $this->collection->map(function ($item) use ($priceBands, $request) {
                $key  = "{$item->country}:{$item->produce_type}";
                $band = $priceBands[$key] ?? new MinMaxPriceDto();
                return (new MaizePriceResource($item, $band))->toArray($request);
            }),
        ];
    }
}
