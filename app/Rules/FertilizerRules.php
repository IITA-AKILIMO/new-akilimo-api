<?php

namespace App\Rules;

class FertilizerRules
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public static function rules()
    {
        return [
            'fertilizer_list' => ['present', 'array'],
            'fertilizer_list.*' => ['array'],
            'fertilizer_list.*.name' => ['required', 'string'],
            'fertilizer_list.*.key' => ['required', 'string'],
            'fertilizer_list.*.weight' => ['required', 'numeric', 'min:0'],
            'fertilizer_list.*.price' => ['required', 'numeric', 'min:0'],
            'fertilizer_list.*.selected' => ['required', 'boolean'],
        ];
    }

    /**
     * Custom messages for validation errors.
     *
     * @return array
     */
    public static function messages()
    {
        return [
            'fertilizer_list.present' => 'Fertilizer list field must be present',
        ];
    }
}
