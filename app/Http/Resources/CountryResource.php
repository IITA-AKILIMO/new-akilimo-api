<?php

namespace App\Http\Resources;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Country $country */
        $country = $this->resource;

        return [
            'id' => $country->id,
            'code' => $country->code,
            'name' => $country->name,
            'active' => $country->active,
            'sort_order' => $country->sort_order,
        ];
    }
}
