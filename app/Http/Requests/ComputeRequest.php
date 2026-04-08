<?php

namespace App\Http\Requests;

use App\Rules\ComputeFieldRules;
use App\Rules\FertilizerRules;
use App\Rules\UserInfoRules;
use Illuminate\Foundation\Http\FormRequest;

class ComputeRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Public API — no authentication required by design.
        return true;
    }

    public function rules(): array
    {
        return array_merge(
            UserInfoRules::rules(),
            ComputeFieldRules::rules(),
            FertilizerRules::rules()
        );
    }

    protected function prepareForValidation(): void
    {
        // No pre-processing needed; email_address and phone_number are optional
        // and stored as-is (null when absent) so analytics queries stay accurate.
    }

    public function messages(): array
    {
        return array_merge(
            UserInfoRules::messages(),
            ComputeFieldRules::messages(),
            FertilizerRules::messages()
        );
    }
}
