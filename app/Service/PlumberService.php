<?php

namespace App\Service;

use App\Data\PlumberComputeData;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class PlumberService
{
    protected string $endpoint;

    protected int $timeout = 5;

    public function __construct()
    {
        $endpoint = config('services.plumbr.endpoint');
        $timeout = config('services.plumbr.timeout');
        $this->endpoint = $endpoint;
        $this->timeout = $timeout;
    }

    /**
     * Sends a compute request to the configured endpoint with the given plumber compute data.
     *
     * @param array $plumberComputeData The data to be sent in the compute request.
     * @return mixed The JSON-decoded response from the endpoint.
     *
     * @throws ConnectionException
     */
    public function sendComputeRequest(PlumberComputeData $plumberComputeData): mixed
    {
        try {
            $response = Http::timeout($this->timeout)
                ->retry(3, 100)
                ->acceptJson()
                ->post($this->endpoint, $plumberComputeData->toArray());

            return $response->json();

        } catch (RequestException $ex) {
            return $ex->response->json();
        }


    }
}
