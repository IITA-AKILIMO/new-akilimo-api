<?php

namespace App\Http\Resources\Collections;

use App\Http\Resources\FertilizerPriceResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FertilizerPriceResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => FertilizerPriceResource::collection($this->collection),
        ];
    }
}
