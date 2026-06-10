<?php

namespace App\Http\Resources;

use App\Models\DefaultPrice;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin DefaultPrice
 */
class DefaultPriceResource extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            /** ISO 3166-1 alpha-2 country code */
            'country' => $this->country,
            /** Item name */
            'item' => $this->item,
            /** Default price */
            'price' => $this->price,
            /** Unit of measurement */
            'unit' => $this->unit,
            /** ISO 4217 currency code */
            'currency' => $this->currency,
            /** Timestamp when created */
            'created_at' => $this->created_at?->toDateTimeString(),
            /** Timestamp when last updated */
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
