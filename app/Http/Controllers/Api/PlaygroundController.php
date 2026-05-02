<?php

namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use App\Service\RecommendationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlaygroundController extends Controller
{
    public function __construct(private readonly RecommendationService $recommendationService)
    {
    }

    public function compute(Request $request): JsonResponse
    {
        $data = $request->validate([
            'country_code' => ['required', 'string', 'size:2'],
            'use_case' => ['required', 'string', 'max:10'],
            'field_size' => ['required', 'numeric', 'min:0.1'],
            'area_unit' => ['required', 'string', 'in:acre,ha,m2,are'],
            'map_lat' => ['required', 'numeric', 'between:-90,90'],
            'map_long' => ['required', 'numeric', 'between:-180,180'],
            'lang' => ['sometimes', 'string', 'size:2'],
            'fertilizer_rec' => ['required', 'boolean'],
            'planting_practices_rec' => ['required', 'boolean'],
            'scheduled_planting_rec' => ['required', 'boolean'],
            'scheduled_harvest_rec' => ['required', 'boolean'],
            'inter_cropping_maize_rec' => ['sometimes', 'boolean'],
            'inter_cropping_potato_rec' => ['sometimes', 'boolean'],
            'planting_date' => ['required', 'date_format:Y-m-d'],
            'harvest_date' => ['required', 'date_format:Y-m-d', 'after:planting_date'],
            'fertilizer_list' => ['required', 'array', 'min:1'],
            'fertilizer_list.*.key' => ['required', 'string'],
            'fertilizer_list.*.name' => ['required', 'string'],
            'fertilizer_list.*.fertilizer_type' => ['required', 'string'],
            'fertilizer_list.*.weight' => ['required', 'numeric', 'min:0'],
            'fertilizer_list.*.price' => ['required', 'numeric', 'min:0'],
            'fertilizer_list.*.selected' => ['required', 'boolean'],
        ]);

        $droidRequest = $this->buildDroidRequest($data);

        try {
            $result = $this->recommendationService->compute($droidRequest);
            return response()->json($result);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $e instanceof \App\Exceptions\RecommendationException ? $e->getCode() : 422);
        }
    }

    private function buildDroidRequest(array $data): array
    {
        $countryCode = strtoupper($data['country_code']);
        $useCase = strtoupper($data['use_case']);
        $lang = $data['lang'] ?? 'en';

        return [
            'user_info' => [
                'device_token' => (string)Str::uuid(),
                'farm_name' => 'Demo Farm',
                'first_name' => 'Demo',
                'last_name' => 'User',
                'user_name' => 'demo_user',
                'email_address' => null,
                'phone_number' => null,
                'gender' => 'M',
                'send_email' => false,
                'send_sms' => false,
                'risk_attitude' => 1,
            ],
            'compute_request' => [
                'farmInformation' => [
                    'country_code' => $countryCode,
                    'use_case' => $useCase,
                    'map_lat' => (float)$data['map_lat'],
                    'map_long' => (float)$data['map_long'],
                    'field_size' => (float)$data['field_size'],
                    'area_unit' => $data['area_unit'],
                ],
                'interCropping' => [
                    'inter_cropped_crop' => null,
                    'inter_cropping_maize_rec' => (bool)($data['inter_cropping_maize_rec'] ?? false),
                    'inter_cropping_potato_rec' => (bool)($data['inter_cropping_potato_rec'] ?? false),
                ],
                'recommendations' => [
                    'lang' => $lang,
                    'fertilizer_rec' => (bool)$data['fertilizer_rec'],
                    'planting_practices_rec' => (bool)$data['planting_practices_rec'],
                    'scheduled_planting_rec' => (bool)$data['scheduled_planting_rec'],
                    'scheduled_harvest_rec' => (bool)$data['scheduled_harvest_rec'],
                ],
                'planting' => [
                    'planting_date' => $data['planting_date'],
                    'planting_date_window' => 0,
                    'harvest_date' => $data['harvest_date'],
                    'harvest_date_window' => 0,
                ],
                'fallow' => [
                    'fallow_type' => 'NONE',
                    'fallow_height' => 0.0,
                    'fallow_green' => false,
                ],
                'tractorCosts' => [
                    'tractor_plough' => false,
                    'tractor_harrow' => false,
                    'tractor_ridger' => false,
                    'cost_lmo_area_basis' => 'ha',
                    'cost_tractor_ploughing' => 0.0,
                    'cost_tractor_harrowing' => 0.0,
                    'cost_tractor_ridging' => 0.0,
                ],
                'manualCosts' => [
                    'cost_manual_ploughing' => 0.0,
                    'cost_manual_harrowing' => 0.0,
                    'cost_manual_ridging' => 0.0,
                ],
                'weedingCosts' => [
                    'cost_weeding_one' => 0.0,
                    'cost_weeding_two' => 0.0,
                ],
                'operationsDone' => [
                    'ploughing_done' => false,
                    'harrowing_done' => false,
                    'ridging_done' => false,
                ],
                'methods' => [
                    'method_ploughing' => 'MANUAL',
                    'method_harrowing' => 'MANUAL',
                    'method_ridging' => 'MANUAL',
                    'method_weeding' => 'MANUAL',
                ],
                'yieldInfo' => [
                    'current_field_yield' => 0.0,
                    'current_maize_performance' => 0.0,
                    'sell_to_starch_factory' => false,
                    'starch_factory_name' => null,
                ],
                'cassava' => [
                    'produce_type' => 'ROOTS',
                    'unit_weight' => 100.0,
                    'unit_price' => 50000.0,
                ],
                'maize' => [
                    'produce_type' => 'DRY_GRAIN',
                    'unit_weight' => 50.0,
                    'unit_price' => 12000.0,
                ],
                'sweetPotato' => [
                    'produce_type' => 'ROOTS',
                    'unit_weight' => 50.0,
                    'unit_price' => 5000.0,
                ],
                'maxInvestment' => 500000.0,
            ],
            'fertilizer_list' => $data['fertilizer_list'],
        ];
    }
}
