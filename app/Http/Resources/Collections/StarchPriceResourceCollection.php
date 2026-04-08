<?php

namespace App\Http\Resources\Collections;

use App\Http\Resources\StarchFactoryResource;
use App\Http\Resources\StarchPriceResource;
use App\Http\Resources\UserFeedbackResource;
use App\Models\Currency;
use App\Models\StarchPrice;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class StarchPriceResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => StarchPriceResource::collection($this->resource),
        ];
    }
}
