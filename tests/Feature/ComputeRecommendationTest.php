<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

beforeEach(fn() => $this->actingAsApiUser());

// ---------------------------------------------------------------------------
// Helpers
// ---------------------------------------------------------------------------

function validComputePayload(): array
{
    return [
        'user_info' => [
            'device_token' => fake()->uuid(),
            'risk_attitude' => 2,
            'user_name' => 'test_user',
            'first_name' => 'Test',
            'last_name' => 'User',
            'gender' => 'M',
            'farm_name' => 'Test Farm',
            'email_address' => 'test@example.com',
            'send_sms' => false,
            'send_email' => false,
        ],
        'compute_request' => [
            'farmInformation' => [
                'country_code' => 'NG',
                'use_case' => 'CASSAVA',
                'map_lat' => 7.4,
                'map_long' => 5.2,
                'field_size' => 1.0,
                'area_unit' => 'ha',
            ],
            'interCropping' => [
                'inter_cropped_crop' => null,
                'inter_cropping_maize_rec' => false,
                'inter_cropping_potato_rec' => false,
            ],
            'recommendations' => [
                'lang' => 'en',
                'fertilizer_rec' => true,
                'planting_practices_rec' => false,
                'scheduled_planting_rec' => false,
                'scheduled_harvest_rec' => false,
            ],
            'planting' => [
                'planting_date' => '2025-04-01',
                'harvest_date' => '2025-10-01',
                'planting_date_window' => 0,
                'harvest_date_window' => 0,
            ],
            'fallow' => [
                'fallow_type' => 'NONE',
                'fallow_height' => 0,
                'fallow_green' => false,
            ],
            'tractorCosts' => [
                'tractor_plough' => false,
                'tractor_harrow' => false,
                'tractor_ridger' => false,
                'cost_lmo_area_basis' => 'ha',
                'cost_tractor_ploughing' => 0,
                'cost_tractor_harrowing' => 0,
                'cost_tractor_ridging' => 0,
            ],
            'manualCosts' => [
                'cost_manual_ploughing' => 0,
                'cost_manual_harrowing' => 0,
                'cost_manual_ridging' => 0,
            ],
            'weedingCosts' => [
                'cost_weeding_one' => 0,
                'cost_weeding_two' => 0,
            ],
            'operationsDone' => [
                'ploughing_done' => false,
                'harrowing_done' => false,
                'ridging_done' => false,
            ],
            'methods' => [
                'method_ploughing' => null,
                'method_harrowing' => null,
                'method_ridging' => null,
                'method_weeding' => null,
            ],
            'yieldInfo' => [
                'current_field_yield' => 10,
                'current_maize_performance' => 5,
                'sell_to_starch_factory' => false,
                'starch_factory_name' => '',
            ],
            'cassava' => [
                'produce_type' => 'FRESH_TUBER',
                'unit_weight' => 100,
                'unit_price' => 5000,
                'unit_price_maize_1' => 0,
                'unit_price_maize_2' => 0,
                'unit_price_potato_1' => 0,
                'unit_price_potato_2' => 0,
            ],
            'maize' => [
                'produce_type' => 'DRY_GRAIN',
                'unit_weight' => 100,
                'unit_price' => 10000,
                'unit_price_maize_1' => 0,
                'unit_price_maize_2' => 0,
                'unit_price_potato_1' => 0,
                'unit_price_potato_2' => 0,
            ],
            'sweetPotato' => [
                'produce_type' => 'FRESH_TUBER',
                'unit_weight' => 100,
                'unit_price' => 3000,
                'unit_price_maize_1' => 0,
                'unit_price_maize_2' => 0,
                'unit_price_potato_1' => 0,
                'unit_price_potato_2' => 0,
            ],
            'maxInvestment' => 50000,
        ],
        'fertilizer_list' => [],
    ];
}

function plumberSuccessResponse(): array
{
    return [
        'status' => 'success',
        'version' => '20251228',
        'data' => [
            'rec_type' => 'FR',
            'recommendation' => 'We recommend applying 50 kg of Urea per hectare',
            'data' => [],
            'fertilizer_rates' => [['type' => 'Urea', 'rate' => 50]],
        ],
    ];
}

// ---------------------------------------------------------------------------
// Tests
// ---------------------------------------------------------------------------

it('returns a recommendation on a successful Plumbr response', function () {
    Http::fake([
        '*' => Http::response(plumberSuccessResponse(), 200),
    ]);

    $response = $this->postJson('/api/v1/recommendations/compute', validComputePayload());

    $response->assertOk()
        ->assertJsonStructure([
            'request_id',
            'status',
            'version',
            'data' => ['rec_type', 'recommendation', 'data', 'fertilizer_rates'],
        ]);
});

it('includes a server-generated request_id in the response', function () {
    Http::fake(['*' => Http::response(plumberSuccessResponse(), 200)]);

    $response = $this->postJson('/api/v1/recommendations/compute', validComputePayload());

    $response->assertOk();
    $requestId = $response->json('request_id');
    expect($requestId)->toBeString()->not->toBeEmpty();
    // Each call must produce a unique ID
    $response2 = $this->postJson('/api/v1/recommendations/compute', validComputePayload());
    expect($response2->json('request_id'))->not->toBe($requestId);
});

it('logs the request to api_requests with device_token and duration', function () {
    Http::fake(['*' => Http::response(plumberSuccessResponse(), 200)]);

    $payload = validComputePayload();
    $payload['user_info']['device_token'] = $deviceToken = fake()->uuid();

    $this->postJson('/api/v1/recommendations/compute', $payload)->assertOk();

    $this->assertDatabaseHas('api_requests', [
        'device_token' => $deviceToken,
    ]);

    $record = \App\Models\ApiRequest::where('device_token', $deviceToken)->first();
    expect($record)->not->toBeNull()
        ->and($record->request_duration_ms)->toBeInt()->toBeGreaterThanOrEqual(0);
});

it('returns 503 when Plumbr is unreachable', function () {
    Http::fake(['*' => fn() => throw new \Illuminate\Http\Client\ConnectionException('timeout')]);

    $this->postJson('/api/v1/recommendations/compute', validComputePayload())
        ->assertStatus(503);
});

it('returns an error when Plumbr responds with a non-2xx status', function () {
    Http::fake(['*' => Http::response(['message' => 'Bad input'], 422)]);

    $this->postJson('/api/v1/recommendations/compute', validComputePayload())
        ->assertStatus(422);
});

it('returns 422 when required fields are missing', function () {
    $this->postJson('/api/v1/recommendations/compute', [])
        ->assertUnprocessable();
});

it('serves subsequent identical requests from cache without calling Plumbr again', function () {
    Http::fake(['*' => Http::response(plumberSuccessResponse(), 200)]);
    Cache::flush();

    $payload = validComputePayload();
    $this->postJson('/api/v1/recommendations/compute', $payload)->assertOk();
    $this->postJson('/api/v1/recommendations/compute', $payload)->assertOk();

    Http::assertSentCount(1);
});

it('does not share cache between requests with different fertilizer selections', function () {
    Http::fake(['*' => Http::response(plumberSuccessResponse(), 200)]);
    Cache::flush();

    $payload1 = validComputePayload();
    $payload2 = validComputePayload();
    $payload2['fertilizer_list'] = [
        [
            'name' => 'Urea',
            'fertilizer_type' => 'STRAIGHT',
            'key' => 'urea',
            'weight' => 50,
            'price' => 12000,
            'selected' => true,
        ],
    ];

    $this->postJson('/api/v1/recommendations/compute', $payload1)->assertOk();
    $this->postJson('/api/v1/recommendations/compute', $payload2)->assertOk();

    Http::assertSentCount(2);
});
