<?php

namespace App\Http\Resources;

use App\Http\Enums\EnumCountry;
use App\Models\Currency;
use App\Models\InvestmentAmount;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvestmentAmountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var InvestmentAmount $model */
        $model = $this->resource;

        $tag = $model->id;
        if ($model->investment_amount === -1.0) {
            $tag = 'exact';
        }

        $currencyCode = EnumCountry::fromCode($model->country)->currency();
        $currency = Currency::whereCurrencyCode($currencyCode)->first();
        $currencyResource = CurrencyResource::make($currency);

        return [
            'id' => $model->id,
            'investment_amount' => $model->investment_amount,
            'country_code' => $model->country,
            'currency' => $currencyResource,
            'exact_amount' => $model->investment_amount === 0.0,
            'item_tag' => $tag,
            'active' => $model->price_active,
            'area_unit' => $model->area_unit,
            'sort_order' => $model->sort_order,
        ];
    }
}
