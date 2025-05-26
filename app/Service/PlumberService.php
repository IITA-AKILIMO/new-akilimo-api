<?php

namespace App\Service;

use App\Data\PlumberComputeData;
use App\Exceptions\RecommendationException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class PlumberService
{
    protected string $baseUrl;
    protected string $endpoint;

    protected int $timeout = 5;

    public function __construct()
    {
        $this->baseUrl = config('services.plumbr.base_url');
        $this->endpoint = config('services.plumbr.rec_endpoint');
        $this->timeout = config('services.plumbr.request_timeout');
    }

    /**
     * Sends a compute request to the configured endpoint with the given plumber compute data.
     *
     * @param array $plumberComputeData The data to be sent in the compute request.
     * @return mixed The JSON-decoded response from the endpoint.
     *
     * @throws ConnectionException
     * @throws RecommendationException
     */
    public function sendComputeRequest(PlumberComputeData $plumberComputeData): mixed
    {

        $response = Http::baseUrl($this->baseUrl)
            ->timeout($this->timeout)
//                ->retry(3, 100)
            ->acceptJson()
            ->post($this->endpoint, $plumberComputeData->toArray());


        $body = $response->json();
        $statusCode = $response->getStatusCode();
        if ($response->successful()) {
            return $response->json();
        }

        throw new RecommendationException(
            "Failed to call Akilimo API",
            $statusCode,
            $body
        );
    }
}
