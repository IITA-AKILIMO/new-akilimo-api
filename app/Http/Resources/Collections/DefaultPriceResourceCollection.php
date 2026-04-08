<?php

namespace App\Http\Resources\Collections;

use App\Http\Resources\DefaultPriceResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DefaultPriceResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => DefaultPriceResource::collection($this->resource),
        ];
    }
}
