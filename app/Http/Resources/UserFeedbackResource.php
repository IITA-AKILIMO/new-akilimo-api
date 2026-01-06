<?php

namespace App\Http\Resources;

use App\Models\StarchFactory;
use App\Models\UserFeedback;
use Illuminate\Http\Resources\Json\JsonResource;

class UserFeedbackResource extends JsonResource
{
    public static $wrap = 'feedback';

    public function toArray($request): array
    {
        /** @var UserFeedback $this */


        return [
            'id' => $this->id,
            'recommendation_id' => $this->id,
            'akilimo_usage' => $this->akilimo_usage,
            'user_type' => $this->user_type,
            'recommendation_rating' => $this->akilimo_rec_rating,
            'useful_rating' => $this->akilimo_useful_rating,
            'language' => $this->language,
            'device_token' => $this->device_token,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
