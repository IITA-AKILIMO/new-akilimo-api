<?php

namespace App\Http\Resources;

use App\Models\Fertilizer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FertilizerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Fertilizer $fertilizer */
        $fertilizer = $this->resource;

        return [
            'id' => $fertilizer->id,
            'name' => $fertilizer->name,
            'type' => $fertilizer->type,
            'key' => $fertilizer->fertilizer_key,
            'weight' => $fertilizer->weight,
            'country' => $fertilizer->country,
            'sort_order' => $fertilizer->sort_order,
            'use_case' => $fertilizer->use_case,
            'available' => $fertilizer->available,
            'created_at' => $fertilizer->created_at,
            'updated_at' => $fertilizer->updated_at,
        ];
    }
}
