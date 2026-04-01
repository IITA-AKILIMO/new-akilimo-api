<?php

namespace App\Http\Resources\Collections;

use App\Data\MinMaxPriceDto;
use App\Http\Enums\EnumCountry;
use App\Http\Resources\CassavaPriceResource;
use App\Models\Currency;
use App\Repositories\CassavaPriceRepo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CassavaPriceResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * Price bands and currencies are loaded once for all rows on this page,
     * eliminating the two N+1 queries the old per-row instantiation caused.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        // One query for all price bands across countries
        /** @var array<string, MinMaxPriceDto> $priceBands */
        $priceBands = app(CassavaPriceRepo::class)->findAllPriceBands();

        // One query for all currencies needed on this page
        $currencyCodes = $this->collection
            ->map(fn($item) => EnumCountry::fromCode($item->country)->currency())
            ->unique()
            ->values()
            ->all();
        $currencies = Currency::whereIn('currency_code', $currencyCodes)
            ->get()
            ->keyBy('currency_code');

        return [
            'data' => $this->collection->map(function ($item) use ($priceBands, $currencies, $request) {
                $band         = $priceBands[$item->country] ?? new MinMaxPriceDto();
                $currencyCode = EnumCountry::fromCode($item->country)->currency();
                $currency     = $currencies->get($currencyCode);
                return (new CassavaPriceResource($item, $band, $currency))->toArray($request);
            }),
        ];
    }
}
