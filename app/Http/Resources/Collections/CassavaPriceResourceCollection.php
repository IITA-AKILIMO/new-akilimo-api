<?php

namespace App\Http\Resources\Collections;

use App\Http\Resources\CassavaPriceResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CassavaPriceResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => CassavaPriceResource::collection($this->collection),
        ];
    }
}
