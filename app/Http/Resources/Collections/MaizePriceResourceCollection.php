<?php

namespace App\Http\Resources\Collections;

class MaizePriceResourceCollection extends \Illuminate\Http\Resources\Json\ResourceCollection
{
    public function toArray($request):array
    {
        return [
            'data' => \App\Http\Resources\MaizePriceResource::collection($this->collection),
        ];
    }
}