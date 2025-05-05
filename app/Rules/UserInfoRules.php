<?php

namespace App\Rules;

class UserInfoRules
{
    public static function rules(): array
    {
        return [
            'user_info.device_token' => ['required', 'string', 'uuid'],
            'user_info.email_address' => ['nullable', 'email'],
            'user_info.field_description' => ['nullable', 'string'],
            'user_info.first_name' => ['required', 'string', 'max:255'],
            'user_info.last_name' => ['required', 'string', 'max:255'],
            'user_info.phone_number' => ['nullable', 'string'],
            'user_info.gender' => ['required', 'string', 'in:M,F,NA'],
            'user_info.send_email' => ['required', 'boolean'],
            'user_info.send_sms' => ['required', 'boolean'],
            'user_info.user_name' => ['required', 'string', 'max:255'],
        ];
    }

    public static function messages(): array
    {
        return [
            'user_info.device_token.uuid' => 'Device token must be a valid UUID.',
        ];
    }
}
