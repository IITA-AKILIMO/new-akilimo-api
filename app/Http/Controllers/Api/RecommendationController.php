<?php

namespace App\Http\Controllers\Api;

use App\Data\ComputeRequestData;
use App\Data\FertilizerData;
use App\Data\PlumberComputeData;
use App\Data\UserInfoData;
use App\Http\Controllers\Controller;
use App\Http\Requests\ComputeRequest;
use Illuminate\Support\Arr;

class RecommendationController extends Controller
{

    public function computeRecommendations(ComputeRequest $request)
    {
        $data = $request->array();
        $userInfo = Arr::get($data, 'user_info');
        $computeRequest = Arr::get($data, 'compute_request');
        $fertilizerList = Arr::get($data, 'fertilizer_list');

        $userInfoData = UserInfoData::from($userInfo);
        $computeRequest = ComputeRequestData::from($computeRequest);

        $fertilizerResult = FertilizerData::collect($fertilizerList);

        $fertilizers = [];
        foreach ($fertilizerResult as $fertilizer) {
            /** @var FertilizerData $fertilizer */
            $fertilizers[] = [
                $fertilizer->type
            ];
        }

//        return $fertilizers;
        $data = [];
        $data = PlumberComputeData::from($computeRequest, $userInfoData);
        // Area unit translation
        if (strcasecmp($data->areaUnit, 'ekari') === 0) {
            $data->areaUnit = 'acre';
        } elseif (strcasecmp($data->areaUnit, 'hekta') === 0) {
            $data->areaUnit = 'ha';
        }

        $data = $data->toArray();
//        ksort($data);

        return [
//            'user'=>$userInfoData,
//            'computeRequest' => $computeRequest,
            'plumberData' => $data
        ];
    }
}
