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
            'country' => $this->country,
            'item' => $this->item,
            'price' => $this->price,
            'unit' => $this->unit,
            'currency' => $this->currency,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
