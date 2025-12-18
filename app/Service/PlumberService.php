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
            // Log the connection error
            \Log::error('Akilimo API Connection Error', [
                'endpoint' => $this->endpoint,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw new ConnectionException(
                "Failed to connect to Akilimo API: " . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }

        $statusCode = $response->status();
        $body = $response->json();

        // Handle invalid JSON responses
        if ($body === null) {
            \Log::error('Akilimo API Invalid JSON Response', [
                'status_code' => $statusCode,
                'raw_body' => $response->body(),
                'endpoint' => $this->endpoint
            ]);

            throw new RecommendationException(
                "Invalid JSON response from Akilimo API",
                $statusCode,
                ['raw_response' => $response->body()]
            );
        }

        // Success case
        if ($response->successful()) {
            \Log::info('Akilimo API Request Successful', [
                'endpoint' => $this->endpoint,
                'status_code' => $statusCode
            ]);
            return $body;
        }

        // Error case - extract message
        $message = $body['message']
            ?? $body['error']
            ?? $body['error_message']
            ?? $body['errorMessage']
            ?? null;

        // Validate message is not empty
        if (empty($message) || !is_string($message) || trim($message) === '') {
            $message = "Failed to call Akilimo API (HTTP {$statusCode})";
        }

        // Log the API error
        \Log::error('Akilimo API Error Response', [
            'status_code' => $statusCode,
            'message' => $message,
            'body' => $body,
            'endpoint' => $this->endpoint
        ]);

        throw new RecommendationException(
            $message,
            $statusCode,
        );
    }

}
