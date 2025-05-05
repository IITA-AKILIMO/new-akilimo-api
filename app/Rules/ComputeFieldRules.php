<?php

namespace App\Rules;

class ComputeFieldRules
{
    public static function rules(): array
    {
        return [
            'compute_request.area_unit' => ['required', 'string', 'in:acre,ha'],
            'compute_request.cass_up_m1' => ['required', 'numeric'],
            'compute_request.cass_up_m2' => ['required', 'numeric'],
            'compute_request.cass_up_p1' => ['required', 'numeric'],
            'compute_request.cass_up_p2' => ['required', 'numeric'],
            'compute_request.cassava_produce_type' => ['required', 'string'],
            'compute_request.cassava_unit_price' => ['required', 'numeric'],
            'compute_request.cassava_unit_weight' => ['required', 'numeric'],
            'compute_request.cost_lmo_area_basis' => ['required', 'string'],
            'compute_request.cost_manual_harrowing' => ['required', 'numeric'],
            'compute_request.cost_manual_ploughing' => ['required', 'numeric'],
            'compute_request.cost_manual_ridging' => ['required', 'numeric'],
            'compute_request.cost_tractor_harrowing' => ['required', 'numeric'],
            'compute_request.cost_tractor_ploughing' => ['required', 'numeric'],
            'compute_request.cost_tractor_ridging' => ['required', 'numeric'],
            'compute_request.cost_weeding_one' => ['required', 'numeric'],
            'compute_request.cost_weeding_two' => ['required', 'numeric'],
            'compute_request.country_code' => ['required', 'string', 'size:2'],
            'compute_request.currency_code' => ['nullable', 'string', 'size:3'],
            'compute_request.current_field_yield' => ['required', 'numeric'],
            'compute_request.current_maize_performance' => ['nullable', 'numeric'],
            'compute_request.fallow_green' => ['required', 'boolean'],
            'compute_request.fallow_height' => ['required', 'numeric'],
            'compute_request.fallow_type' => ['required', 'string'],
            'compute_request.fertilizer_rec' => ['required', 'boolean'],
            'compute_request.field_size' => ['required', 'numeric'],
            'compute_request.harrowing_done' => ['required', 'boolean'],
            'compute_request.harvest_date' => ['nullable', 'date'],
            'compute_request.harvest_date_window' => ['required', 'numeric'],
            'compute_request.inter_crop' => ['required', 'boolean'],
            'compute_request.inter_cropping_maize_rec' => ['required', 'boolean'],
            'compute_request.inter_cropping_potato_rec' => ['required', 'boolean'],
            'compute_request.inter_cropping_type' => ['nullable', 'string'],
            'compute_request.maize_produce_type' => ['required', 'string'],
            'compute_request.maize_unit_price' => ['required', 'numeric'],
            'compute_request.maize_unit_weight' => ['required', 'numeric'],
            'compute_request.map_lat' => ['required', 'numeric'],
            'compute_request.map_long' => ['required', 'numeric'],
            'compute_request.max_investment' => ['required', 'numeric'],
            'compute_request.method_harrowing' => ['nullable', 'string'],
            'compute_request.method_ploughing' => ['nullable', 'string'],
            'compute_request.method_ridging' => ['nullable', 'string'],
            'compute_request.method_weeding' => ['nullable', 'string'],
            'compute_request.planting_date' => ['nullable', 'date'],
            'compute_request.planting_date_window' => ['required', 'numeric'],
            'compute_request.planting_practices_rec' => ['required', 'boolean'],
            'compute_request.ploughing_done' => ['required', 'boolean'],
            'compute_request.problem_weeds' => ['required', 'boolean'],
            'compute_request.ridging_done' => ['required', 'boolean'],
            'compute_request.risk_attitude' => ['required', 'integer'],
            'compute_request.scheduled_harvest_rec' => ['required', 'boolean'],
            'compute_request.scheduled_planting_rec' => ['required', 'boolean'],
            'compute_request.sell_to_starch_factory' => ['required', 'boolean'],
            'compute_request.starch_factory_name' => ['nullable', 'string'],
            'compute_request.sweet_potato_produce_type' => ['required', 'string'],
            'compute_request.sweet_potato_unit_price' => ['required', 'numeric'],
            'compute_request.sweet_potato_unit_weight' => ['required', 'numeric'],
            'compute_request.tractor_harrow' => ['required', 'boolean'],
            'compute_request.tractor_plough' => ['required', 'boolean'],
            'compute_request.tractor_ridger' => ['required', 'boolean'],
            'compute_request.use_case' => ['required', 'string'],
        ];
    }

    public static function messages(): array
    {
        return [
            '*.required' => 'The :attribute field is required.',
            '*.numeric' => 'The :attribute must be a number.',
            '*.string' => 'The :attribute must be a string.',
            '*.boolean' => 'The :attribute must be true or false.',
            '*.in' => 'The selected value for :attribute is invalid.',
            '*.min' => 'The :attribute must be at least :min.',
            '*.size' => 'The :attribute must be exactly :size characters.',
            '*.date_format' => 'The :attribute must be in the format m/d/Y.',
            '*.between' => 'The :attribute must be between :min and :max.',
            '*.nullable' => 'The :attribute can be null.',
        ];
    }
}
