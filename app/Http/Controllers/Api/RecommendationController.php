<?php

namespace App\Http\Controllers\Api;

use App\Data\ComputeRequestData;
use App\Data\FertilizerData;
use App\Data\PlumberComputeData;
use App\Data\UserInfoData;
use App\Http\Controllers\Controller;
use App\Http\Requests\ComputeRequest;
use App\Repositories\FertilizerRepo;
use App\Service\PlumberService;
use Illuminate\Support\Arr;

class RecommendationController extends Controller
{
//    protected PlumberService $service;

    public function __construct(protected FertilizerRepo $repo, protected PlumberService $service)
    {

    }

    public function computeRecommendations(ComputeRequest $request)
    {
        $data = $request->array();
        $userInfo = Arr::get($data, 'user_info');
        $computeRequest = Arr::get($data, 'compute_request');
        $fertilizerList = Arr::get($data, 'fertilizer_list');

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


        $data = PlumberComputeData::from($computeRequest, $userInfoData, $fertilizers);
        // Area unit translation
        if (strcasecmp($data->areaUnit, 'ekari') === 0) {
            $data->areaUnit = 'acre';
        } elseif (strcasecmp($data->areaUnit, 'hekta') === 0) {
            $data->areaUnit = 'ha';
        }

        $data = $data->toArray();

        $resp = $this->service->sendComputeRequest(plumberComputeData: $data);

        return $resp;
        return [
            //            'user'=>$userInfoData,
            //            'computeRequest' => $computeRequest,
            'plumberData' => $data,
        ];
    }
}
