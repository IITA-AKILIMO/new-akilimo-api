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

    public function messages(): array
    {
        return array_merge(
            UserInfoRules::messages(),
            ComputeFieldRules::messages(),
            FertilizerRules::messages()
        );
    }

}
