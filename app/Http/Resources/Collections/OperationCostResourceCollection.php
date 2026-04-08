<?php

namespace App\Http\Resources\Collections;

use App\Http\Resources\OperationCostResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OperationCostResourceCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => OperationCostResource::collection($this->collection),
        ];
    }
}
