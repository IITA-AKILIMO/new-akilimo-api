<?php

namespace App\Http\Resources\Collections;

use App\Http\Resources\CurrencyResource;
use App\Http\Resources\UserFeedbackResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserFeedbackResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => UserFeedbackResource::collection($this->collection),
        ];
    }
}
