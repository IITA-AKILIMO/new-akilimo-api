<?php

namespace App\Http\Resources\Collections;

class OperationCostResourceCollection extends \Illuminate\Http\Resources\Json\ResourceCollection
{
    public function toArray($request):array
    {
        return [
            'data' => \App\Http\Resources\OperationCostResource::collection($this->collection),
        ];
    }
}