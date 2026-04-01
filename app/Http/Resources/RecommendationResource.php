<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecommendationResource extends JsonResource
{
    /**
     * Keep the compute response flat (no 'data' wrapper) to match the shape
     * clients already depend on: { request_id, version, rec_type, recommendation }.
     */
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'request_id'     => $this->resource['request_id'] ?? null,
            'version'        => $this->resource['version'] ?? null,
            'rec_type'       => $this->resource['rec_type'] ?? null,
            'recommendation' => $this->resource['recommendation'] ?? null,
        ];
    }
}
