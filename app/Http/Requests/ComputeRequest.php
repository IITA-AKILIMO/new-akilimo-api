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

    protected function prepareForValidation(): void
    {
        $userInfo = $this->input('user_info', []);

        $this->merge([
            'user_info' => array_merge($userInfo, [
                'email_address' => filled($userInfo['email_address'] ?? null)
                    ? $userInfo['email_address']
                    : 'akilimo@cgiar.org',

                'phone_number' => filled($userInfo['phone_number'] ?? null)
                    ? $userInfo['phone_number']
                    : '0000000000',
            ]),
        ]);
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
