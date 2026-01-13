<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeedBackRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'akilimo_usage' => ['required', 'string'],
            'user_type' => ['required', 'string'],
            'device_token' => ['required', 'string'],
            'device_language' => ['required', 'string', 'in:en,fr,sw'],
            'satisfaction_rating' => ['required', 'integer', 'between:1,5'],
            'nps_score' => ['required', 'integer', 'between:0,10'],
            'use_case' => ['required', 'string'],
        ];
    }

    /**
     * Maps the user type to the enum value.
     */
    public function mapUserType(string $userType): string
    {
        return match (strtolower($userType)) {
            'extension_agent', 'ea' => 'EA',
            'community_member' => 'COMMUNITY_MEMBER',
            'farmer' => 'FARMER',
            default => 'OTHER',
        };
    }

    /**
     * Returns the validated data in the format expected by the external service.
     */
    public function toPersistenceArray(): array
    {
        $validated = $this->validated();

        return [
            'akilimo_usage' => $validated['akilimo_usage'],
            'user_type' => $this->mapUserType($validated['user_type']),
            'device_token' => $validated['device_token'],
            'use_case' => $validated['use_case'],
            'language' => $validated['device_language'],
            'akilimo_rec_rating' => $validated['satisfaction_rating'],
            'akilimo_useful_rating' => $validated['nps_score'],
        ];
    }

}
