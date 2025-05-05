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
            'fertilizer_list.*.id' => ['required', 'integer'],
            'fertilizer_list.*.image_id' => ['required', 'integer'],
            'fertilizer_list.*.name' => ['required', 'string', 'max:255'],
            'fertilizer_list.*.weight' => ['required', 'numeric', 'min:0'],
            'fertilizer_list.*.available' => ['required', 'boolean'],
            'fertilizer_list.*.price' => ['required', 'numeric', 'min:0'],
            'fertilizer_list.*.selected' => ['required', 'boolean'],
            'fertilizer_list.*.custom' => ['required', 'boolean'],
            'fertilizer_list.*.cim_available' => ['required', 'boolean'],
            'fertilizer_list.*.cis_available' => ['required', 'boolean'],
            'fertilizer_list.*.country_code' => ['required', 'string', 'size:2'],
            'fertilizer_list.*.created_at' => ['required', 'date'],
            'fertilizer_list.*.currency_code' => ['required', 'string', 'size:3'],
            'fertilizer_list.*.exact_price' => ['required', 'boolean'],
            'fertilizer_list.*.fertilizer_key' => ['required', 'string'],
            'fertilizer_list.*.fertilizer_type' => ['required', 'string'],
            'fertilizer_list.*.price_per_bag' => ['required', 'numeric', 'min:0'],
            'fertilizer_list.*.price_range' => ['required', 'string'],
            'fertilizer_list.*.sort_order' => ['required', 'integer'],
            'fertilizer_list.*.updated_at' => ['required', 'date'],
            'fertilizer_list.*.use_case' => ['required', 'string'],
            'fertilizer_list.*.kcontent' => ['required', 'numeric', 'min:0'],
            'fertilizer_list.*.ncontent' => ['required', 'numeric', 'min:0'],
            'fertilizer_list.*.pcontent' => ['required', 'numeric', 'min:0'],
            'fertilizer_list.*.stability' => ['required', 'numeric', 'min:0'],
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
            'fertilizer_list.array' => 'Fertilizer list must be an array',
            'fertilizer_list.*.id.required' => 'Fertilizer ID is required',
            'fertilizer_list.*.name.required' => 'Fertilizer name is required',
            'fertilizer_list.*.weight.required' => 'Fertilizer weight is required',
            'fertilizer_list.*.weight.numeric' => 'Fertilizer weight must be a number',
            'fertilizer_list.*.price.required' => 'Fertilizer price is required',
            'fertilizer_list.*.price.numeric' => 'Fertilizer price must be a number',
            'fertilizer_list.*.country_code.size' => 'Country code must be 2 characters',
            'fertilizer_list.*.currency_code.size' => 'Currency code must be 3 characters',
            'fertilizer_list.*.created_at.date' => 'Created at must be a valid date',
            'fertilizer_list.*.updated_at.date' => 'Updated at must be a valid date',
        ];
    }
}
