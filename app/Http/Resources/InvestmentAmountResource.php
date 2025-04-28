<?php

namespace App\Http\Resources;

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
        /** @var InvestmentAmount $amount */
        $amount = $this->resource;

        $tag = $amount->id;
        if ($amount->investment_amount === -1.0) {
            $tag = 'exact';
        }

        return [
            'id' => $amount->id,
            'investment_amount' => $amount->investment_amount,
            'country_code' => $amount->country,
            'exact_amount' => $amount->investment_amount === 0.0,
            'item_tag' => $tag,
            'active' => $amount->price_active,
            'area_unit' => $amount->area_unit,
            'sort_order' => $amount->sort_order,
        ];
    }
}
