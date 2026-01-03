<?php

namespace App\Http\Resources\Collections;

use App\Http\Resources\CassavaUnitResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CassavaUnitResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => CassavaUnitResource::collection($this->collection),
        ];
    }
}
