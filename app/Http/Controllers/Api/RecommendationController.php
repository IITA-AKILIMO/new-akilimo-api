<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ComputeRequest;

class RecommendationController extends Controller
{

    public function computeRecommendations(ComputeRequest $request)
    {
        return $request;
    }
}
