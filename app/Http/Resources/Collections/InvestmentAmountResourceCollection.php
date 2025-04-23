<?php

namespace App\Http\Resources\Collections;

use App\Http\Resources\InvestmentAmountResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class InvestmentAmountResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => InvestmentAmountResource::collection($this->collection),
        ];
    }
}
