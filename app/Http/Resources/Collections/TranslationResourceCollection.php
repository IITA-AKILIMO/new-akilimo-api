<?php

namespace App\Http\Resources\Collections;

use App\Http\Resources\TranslationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TranslationResourceCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => TranslationResource::collection($this->collection),
        ];
    }
}
