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
            'id'                  => $this->id,
            'request_id'          => $this->request_id,
            'device_token'        => $this->device_token,
            'droid_request'       => $this->droid_request,
            'request_duration_ms' => $this->request_duration_ms,
            'created_at'          => $this->created_at?->toDateTimeString(),
            'updated_at'          => $this->updated_at?->toDateTimeString(),
        ];
    }
}
