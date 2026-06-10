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
            /** Unique identifier for the country */
            'id' => $country->id,
            /** ISO 3166-1 alpha-2 country code */
            'code' => $country->code,
            /** Full name of the country */
            'name' => $country->name,
            /** Whether the country is active */
            'active' => $country->active,
            /** Sort order for display */
            'sort_order' => $country->sort_order,
        ];
    }
}
