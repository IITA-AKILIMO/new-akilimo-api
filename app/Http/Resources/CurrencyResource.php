<?php

namespace App\Http\Resources;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Currency $currency */
        $currency = $this->resource;

        return [
            'id' => $currency->id,
            'country' => $currency->country ?? 'NA',
            'country_code' => $currency->country_code ?? 'NA',
            'name' => $currency->currency_name,
            'currency_code' => $currency->currency_code,
            'currency_symbol' => $currency->currency_symbol,
            'currency_native_symbol' => $currency->currency_native_symbol,
            'currency_name_plural' => $currency->name_plural,
//            'created_at' => $currency->created_at,
//            'updated_at' => $currency->updated_at,
        ];
    }
}
