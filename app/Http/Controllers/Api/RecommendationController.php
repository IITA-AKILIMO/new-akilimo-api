<?php

namespace App\Http\Controllers\Api;

use App\Data\ComputeRequestData;
use App\Data\FertilizerData;
use App\Data\PlumberComputeData;
use App\Data\UserInfoData;
use App\Http\Controllers\Controller;
use App\Http\Requests\ComputeRequest;
use App\Repositories\ApiRequestRepo;
use App\Repositories\FertilizerRepo;
use App\Service\PlumberService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Arr;

class RecommendationController extends Controller
{
    //    protected PlumberService $service;

    public function __construct(
        protected FertilizerRepo $repo,
        protected ApiRequestRepo $apiRequestRepo,
        protected PlumberService $service
    )
    {
    }

    /**
     * Processes the computation of fertilizer recommendations based on the provided request data.
     *
     * Extracts user information, computation details, and fertilizer list from the request object.
     * Retrieves available fertilizers from the repository based on specific conditions (e.g., country and availability).
     * Maps the retrieved fertilizers and updates their properties (e.g., selection status, weight, and price) based on the provided fertilizer list.
     * Generates a new compute request using the user information, computation details, and processed fertilizers.
     * Adjusts the area unit in the request to standardized values for further processing.
     * Sends the request to the computation service and returns the original request data, the computed response, and the final request sent to the service.
     *
     * @param ComputeRequest $request The incoming compute request containing user data, computation details, and a list of fertilizers.
     * @return array An associative array containing:
     *               - 'droidRequest': The original request data extracted from the incoming ComputeRequest.
     *               - 'plumberResponse': The response returned from the computation service.
     *               - 'plumberRequest': The final formatted request sent to the computation service.
     *
     * @throws ConnectionException
     * @throws RequestException
     */
    public function computeRecommendations(ComputeRequest $request)
    {
        $droidRequest = $request->array();
        $userInfo = Arr::get($droidRequest, 'user_info', '{}');
        $computeRequest = Arr::get($droidRequest, 'compute_request', '{}');
        $fertilizerList = Arr::get($droidRequest, 'fertilizer_list', '[]');

        $deviceToken = Arr::get($userInfo, 'device_token', 'NA');

        $userInfoData = UserInfoData::from($userInfo);
        $computeRequest = ComputeRequestData::from($computeRequest);

        $conditions = [
            'country' => $computeRequest->countryCode,
            'available' => true,
        ];

        $columns = [
            'fertilizer_label as fertilizer_label',
            'name as name',
            'weight as weight',
            'fertilizer_key as key',
            'type as fertilizer_type',
        ];
        $availableFertilizers = $this->repo->selectByCondition(
            conditions: $conditions,
            columns: $columns,
        );

        $allFertilizers = FertilizerData::collect($availableFertilizers);
        $fertilizerResult = FertilizerData::collect($fertilizerList);

        // Index fertilizerResult by key for quick lookup
        $fertilizerResultMap = [];
        foreach ($fertilizerResult as $result) {
            $fertilizerResultMap[$result->key] = $result;
        }

        $fertilizers = [];
        foreach ($allFertilizers as $fertilizer) {
            /** @var FertilizerData $fertilizer */
            $key = $fertilizer->key;
            $label = $fertilizer->label;

            // Default values
            $selected = $fertilizer->selected;
            $weight = $fertilizer->weight;
            $pricePerBag = $fertilizer->pricePerBag;

            // If there's a result entry for this label, update the values
            if (isset($fertilizerResultMap[$key])) {
                $result = $fertilizerResultMap[$key];
                $selected = $result->selected;
                $weight = $result->weight;
                $pricePerBag = $result->pricePerBag;
            }

            $fertilizers["{$label}available"] = $selected;
            $fertilizers["{$label}BagWt"] = $weight;
            $fertilizers["{$label}CostperBag"] = $pricePerBag;
        }

        $plumberRequest = PlumberComputeData::from($computeRequest, $userInfoData, $fertilizers);
        // Area unit translation
        if (strcasecmp($plumberRequest->areaUnit, 'ekari') === 0) {
            $plumberRequest->areaUnit = 'acre';
        } elseif (strcasecmp($plumberRequest->areaUnit, 'hekta') === 0) {
            $plumberRequest->areaUnit = 'ha';
        }

        $requestData = [
            'request_id' => $deviceToken,
            'droid_request' => $droidRequest,
            'plumber_request' => $plumberRequest,
        ];

        $result = $this->apiRequestRepo->create($requestData);

        $plumberResp = $this->service->sendComputeRequest(plumberComputeData: $plumberRequest);
        $plumberData = Arr::get($plumberResp, 'data', '{}');


        $this->apiRequestRepo->update(
            id: $result->id,
            data: [
                'plumber_response' => $plumberData
            ]);

        return [
            'recommendation' => Arr::get($plumberData, 'recommendation'),
        ];
    }
}
