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
    public function __construct($resource, private readonly CassavaPriceRepo $priceRepo)
    {
        parent::__construct($resource);
    }

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
        $countries = $this->collection
            ->pluck('country')
            ->unique()
            ->values();

        $priceBands = $this->priceRepo
            ->findPriceBandsForCountries($countries->all());

        $currencies = Currency::query()
            ->whereIn(
                'currency_code',
                $countries
                    ->map(fn ($country) => EnumCountry::fromCode($country)->currency())
                    ->unique()
                    ->all()
            )
            ->get()
            ->keyBy('currency_code');

        $this->collection->each(function ($item) use ($priceBands, $currencies) {
            $currencyCode = EnumCountry::fromCode($item->country)->currency();

            $item->priceBand = $priceBands[$item->country] ?? null;
            $item->currency = $currencies[$currencyCode] ?? null;
        });

        return CassavaPriceResource::collection($this->collection)->toArray($request);
    }
}
