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
     * @param PlumberComputeData $plumberComputeData The data to be sent in the compute request.
     * @return array The JSON-decoded response from the endpoint.
     *
     * @throws ConnectionException If the HTTP request fails due to network issues.
     * @throws RecommendationException If the API responds with an error or non-success status.
     */
    public function sendComputeRequest(PlumberComputeData $plumberComputeData): array
    {
        try {
            $response = Http::baseUrl($this->baseUrl)
                ->timeout($this->timeout)
                ->acceptJson()
                ->post($this->endpoint, $plumberComputeData->toArray());
        } catch (\Exception $e) {
            // Network or connection error
            throw new ConnectionException(
                "Failed to connect to Akilimo API: " . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }

        $body = $response->json();
        $statusCode = $response->getStatusCode();

        if ($response->successful()) {
            return $body;
        }

        // API responded but with an error
        $message = $body['message'] ?? "Failed to call Akilimo API";
        throw new RecommendationException(
            $message,
            $statusCode,
            $body
        );
    }

}
