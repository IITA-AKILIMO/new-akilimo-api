<?php

namespace App\Http\Resources;

use App\Models\StarchFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StarchFactoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        /** @var StarchFactory $starchFactory */
        $starchFactory = $this->resource;

        return [
            'id' => $starchFactory->id,
            'factory_name' => $starchFactory->factory_name,
            'factory_label' => $starchFactory->factory_label,
            'country_code' => $starchFactory->country,
            'sort_order' => $starchFactory->sort_order,
            'factory_active' => $starchFactory->factory_active,
            'created_at' => $starchFactory->created_at,
            'updated_at' => $starchFactory->updated_at,
        ];
    }
}
