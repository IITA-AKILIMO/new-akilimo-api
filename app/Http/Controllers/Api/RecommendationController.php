<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\RecommendationException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ComputeRequest;
use App\Service\RecommendationService;
use Illuminate\Http\Client\ConnectionException;

class RecommendationController extends Controller
{

    public function __construct(
        protected RecommendationService $recommendationService,
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
     * @throws RecommendationException
     */
    public function computeRecommendations(ComputeRequest $request): array
    {
        return $this->recommendationService->compute(droidRequest: $request->toArray());
    }
}
