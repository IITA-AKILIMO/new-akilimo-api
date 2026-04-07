<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecommendationResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'request_id' => $this->resource['request_id'] ?? null,
            'status'     => $this->resource['status'] ?? 'success',
            'version'    => $this->resource['version'] ?? null,
            'data'       => $this->resource['data'] ?? [],
        ];
    }
}
