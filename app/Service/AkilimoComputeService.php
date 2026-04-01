<?php

namespace App\Service;

use App\Data\AkilimoComputeData;
use App\Exceptions\RecommendationException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AkilimoComputeService
{
    public function __construct(
        protected string $baseUrl = '',
        protected string $endpoint = '',
        protected int    $timeout = 120,
        protected int    $retries = 3,
        protected bool   $logging = true,
    )
    {
        $this->baseUrl = config('akilimo-compute.base_url');
        $this->endpoint = config('akilimo-compute.endpoint');
        $this->timeout = config('akilimo-compute.timeout');
        $this->retries = config('akilimo-compute.retries');
        $this->logging = config('akilimo-compute.logging');
    }

    /**
     * Send compute request to Akilimo API.
     *
     * @throws ConnectionException|RecommendationException
     */
    public function compute(AkilimoComputeData $data): array
    {
        try {
            $response = Http::baseUrl($this->baseUrl)
                ->timeout($this->timeout)
                ->retry($this->retries)
                ->acceptJson()
                ->post($this->endpoint, $data->toArray());
        } catch (ConnectionException $e) {
            Log::error('AKILIMO Compute API Connection Error', ['endpoint' => $this->endpoint, 'error' => $e->getMessage()]);
            throw $e;
        } catch (\Illuminate\Http\Client\RequestException $e) {
            // HTTP-level error after retries — let the caller handle status code mapping
            throw $e;
        } catch (\Throwable $e) {
            Log::error('AKILIMO API Unexpected Error', ['endpoint' => $this->endpoint, 'error' => $e->getMessage()]);
            throw new RecommendationException('Unexpected error calling AKILIMO Compute API', 500);
        }

        $body = $response->json();

        if ($body === null) {
            Log::error('AKILIMO API Invalid JSON', [
                'status_code' => $response->status(),
                'raw_body' => $response->body(),
                'endpoint' => $this->endpoint,
            ]);
            throw new RecommendationException("Invalid JSON response from AKILIMO Compute API", $response->status());
        }

        if ($response->successful()) {
            $this->log('info', 'AKILIMO API Success', $response);
            return $body;
        }

        $message = $body['message']
            ?? $body['error']
            ?? $body['error_message']
            ?? $body['errorMessage']
            ?? "Failed to call AKILIMO API (HTTP {$response->status()})";

        $this->log('error', 'AKILIMO API Error', $response, $message, $body);

        throw new RecommendationException($message, $response->status());
    }

    protected function log(string $level, string $context, $response, ?string $message = null, ?array $body = null): void
    {
        if (!$this->logging) {
            return;
        }

        Log::$level($context, [
            'endpoint' => $this->endpoint,
            'status_code' => $response->status(),
            'message' => $message,
            'body' => $body,
        ]);
    }
}
