<?php

namespace App\Http\Resources;

use App\Models\ApiRequest;
use App\Models\UserFeedback;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiRequestResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var ApiRequest $this */

        return [
            'id' => $this->id,
            'request_id' => $this->request_id,
            'droid_request' => $this->droid_request,
//            'plumber_request' => json_decode($this->plumber_request, true),
//            'plumber_response' => json_decode($this->plumber_response, true),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
