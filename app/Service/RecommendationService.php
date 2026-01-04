<?php

namespace App\Service;

use App\Data\ComputeRequestData;
use App\Data\FertilizerData;
use App\Data\PlumberComputeData;
use App\Data\UserInfoData;
use App\Exceptions\RecommendationException;
use App\Repositories\ApiRequestRepo;
use App\Repositories\FertilizerRepo;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class RecommendationService
{
    protected int|\DateTimeInterface $cacheTTL;

    public function __construct(
        protected FertilizerRepo $fertilizerRepo,
        protected ApiRequestRepo $apiRequestRepo,
        protected PlumberService $plumberService,
    )
    {
        $ttl = config('cache.ttl'); // e.g. "1d", "1h", "30m"
        $this->cacheTTL = $this->parseTtl($ttl);
    }

    /**
     * Handles the entire recommendation computation workflow.
     *
     * @param array $droidRequest The parsed request payload.
     * @return array Contains 'rec_type' and 'recommendation'.
     *
     * @throws \JsonException
     */
    public function compute(array $droidRequest): array
    {
        $cacheKey = $this->generateCacheKey($droidRequest);
        return Cache::remember($cacheKey, $this->cacheTTL, function () use ($droidRequest) {
            return $this->performComputation($droidRequest);
        });
    }


    /**
     * Performs computation based on the droid request data.
     *
     * @param array $droidRequest The input data containing user information, compute request, and fertilizer list.
     * @return array An array containing the recommended type and recommendations.
     *
     * @throws RecommendationException If there is an error specific to the recommendation process.
     * @throws Exception If a general exception occurs during the computation process.
     */
    private function performComputation(array $droidRequest): array
    {
        $userInfoArray = Arr::get($droidRequest, 'user_info', []);
        $computeRequestArray = Arr::get($droidRequest, 'compute_request', []);
        $fertilizerList = Arr::get($droidRequest, 'fertilizer_list', []);
        $deviceToken = Arr::get($userInfoArray, 'device_token', 'NA');

        $userInfo = UserInfoData::from($userInfoArray);


        $computeRequest = ComputeRequestData::from($computeRequestArray);
        $availableFertilizers = FertilizerData::collect($this->getAvailableFertilizers($computeRequest->farmInformation->countryCode));
        $requestedFertilizers = FertilizerData::collect($fertilizerList);
        $fertilizerMap = collect($requestedFertilizers)->keyBy('key');

        $computedFertilizers = $this->mapFertilizersToExternalFormat($availableFertilizers, $fertilizerMap);

        $plumberRequest = PlumberComputeData::from($computeRequest->toArray(), $userInfo, $computedFertilizers);


        $plumberRequest->areaUnit = $this->normalizeAreaUnit($plumberRequest->areaUnit);

        $requestLog = $this->logRequest($deviceToken, $droidRequest, $plumberRequest);


        try {
            $plumberResp = $this->plumberService->sendComputeRequest($plumberRequest);

            $this->updateRequestLogResponse($requestLog->id, $plumberResp);


            return [
                'version' => Arr::get($plumberResp, 'version'),
                'rec_type' => Arr::get($plumberResp, 'rec_type'),
                'recommendation' => Arr::get($plumberResp, 'recommendation'),
            ];
        } catch (RecommendationException $ex) {
            $this->updateRequestLogResponse($requestLog->id, $ex->body);
            throw $ex;
        } catch (\Illuminate\Http\Client\ConnectionException $ex) {
            // Network/timeout/DNS failures
            $errorBody = [
                'error' => 'Connection failed',
                'message' => 'Unable to connect to recommendation service',
                'details' => $ex->getMessage(),
            ];

            $this->updateRequestLogResponse($requestLog->id, $errorBody);

            throw RecommendationException::serviceUnavailable(
                'Recommendation service is unreachable'
            );
        } catch (\Illuminate\Http\Client\RequestException $ex) {
            // HTTP errors (4xx, 5xx responses)
            $statusCode = $ex->response?->status() ?? 500;
            $responseBody = $ex->response?->json() ?? [];

            $errorBody = [
                'error' => 'Service request failed',
                'message' => $ex->getMessage(),
                'status_code' => $statusCode,
                'response' => $responseBody,
            ];

            $this->updateRequestLogResponse($requestLog->id, $errorBody);

            throw new RecommendationException(
                Arr::get($responseBody, 'message', 'Recommendation service returned an error'),
                $statusCode,
                $errorBody,
                $ex
            );
        } catch (Exception $ex) {
            // Unexpected exceptions
            $errorBody = [
                'error' => 'Unexpected error',
                'message' => $ex->getMessage(),
                'type' => get_class($ex),
            ];

            $this->updateRequestLogResponse($requestLog->id, $errorBody);

            throw new RecommendationException(
                $ex->getMessage() ?: 'Failed to compute recommendation',
                500,
                $errorBody,
                $ex
            );
        }

    }

    /**
     * Generates a unique cache key based on the droidRequest payload.
     *
     * @param array $droidRequest
     * @return string
     * @throws \JsonException
     */
    private function generateCacheKey(array $droidRequest): string
    {
        $relevantData = [
//            'user_info' => Arr::get($droidRequest, 'user_info', []),
            'compute_request' => Arr::get($droidRequest, 'compute_request', []),
//            'fertilizer_list' => Arr::get($droidRequest, 'fertilizer_list', []),
        ];

        // Sort recursively for consistent ordering
        ksort($relevantData);
        array_walk_recursive($relevantData, function (&$value, $key) {
            if (is_array($value)) {
                ksort($value);
            }
        });

        // Use SHA-256 or xxHash (faster for non-crypto use)
        return 'rec:' . hash('sha256', json_encode($relevantData, JSON_THROW_ON_ERROR));
    }

    /**
     * Retrieves available fertilizers for a specific country.
     *
     * @param string $countryCode
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getAvailableFertilizers(string $countryCode): \Illuminate\Database\Eloquent\Collection
    {
        return $this->fertilizerRepo->selectByCondition(
            conditions: [
                'country' => $countryCode,
                'available' => true,
            ],
            columns: [
                'fertilizer_label as fertilizer_label',
                'name as name',
                'weight as weight',
                'fertilizer_key as key',
                'type as fertilizer_type',
            ]
        );
    }

    /**
     * Maps fertilizers to the external service format.
     *
     * @param Collection $availableFertilizers
     * @param Collection $fertilizerMap
     * @return array
     */
    private function mapFertilizersToExternalFormat(Collection $availableFertilizers, Collection $fertilizerMap): array
    {
        $computedFertilizers = [];

        foreach ($availableFertilizers as $fertilizer) {
            $key = $fertilizer->key ?? null;
            $label = $fertilizer->label ?? null;

            // Skip if key or label missing (data integrity)
            if (!$key || !$label) {
                continue;
            }

            $selected = $fertilizer->selected ?? false;
            $weight = $fertilizer->weight ?? 0;
            $price = $fertilizer->pricePerBag ?? 0;

            if ($fertilizerMap->has($key)) {
                $matched = $fertilizerMap->get($key);
                $selected = $matched->selected ?? $selected;
                $weight = $matched->weight ?? $weight;
                $price = $matched->pricePerBag ?? $price;
            }

            $computedFertilizers["{$label}available"] = $selected;
            $computedFertilizers["{$label}BagWt"] = $weight;
            $computedFertilizers["{$label}CostperBag"] = $price;
        }

        return $computedFertilizers;
    }

    /**
     * Normalizes area units to expected values.
     *
     * @param string $areaUnit
     * @return string
     */
    private function normalizeAreaUnit(string $areaUnit): string
    {
        return match (strtolower($areaUnit)) {
            'ekari' => 'acre',
            'hekta' => 'ha',
            default => strtolower($areaUnit),
        };
    }

    /**
     * Logs the incoming request to the database.
     *
     * @param string $deviceToken
     * @param array $droidRequest
     * @param PlumberComputeData $plumberRequest
     * @return object The created log entry.
     */
    private function logRequest(string $deviceToken, array $droidRequest, PlumberComputeData $plumberRequest): object
    {
        return $this->apiRequestRepo->create([
            'request_id' => $deviceToken,
            'droid_request' => $droidRequest,
            'plumber_request' => $plumberRequest,
        ]);
    }

    /**
     * Updates the log entry with the response from the external service.
     *
     * @param int $logId
     * @param array $responseData
     * @return void
     */
    private function updateRequestLogResponse(int $logId, array $responseData): void
    {
        $this->apiRequestRepo->update($logId, [
            'plumber_response' => $responseData,
        ]);
    }

    /**
     * Parses a TTL (time-to-live) string and converts it to a Carbon instance.
     *
     * @param string $ttl The TTL string to parse. It can be a numeric value in seconds
     *                    or a string with a unit (e.g., "10d" for 10 days, "5h" for 5 hours, "30m" for 30 minutes).
     * @return Carbon The calculated expiration time as a Carbon instance.
     */
    private function parseTtl(string $ttl): Carbon
    {

        $now = Carbon::now();

        if (is_numeric($ttl)) {
            return $now->addSeconds((int)$ttl);
        }


        if (preg_match('/^(\d+)([dhm])$/', $ttl, $matches)) {
            $value = (int)$matches[1];
            $unit = $matches[2];

            return match ($unit) {
                'd' => $now->addDays($value),
                'h' => $now->addHours($value),
                'm' => $now->addMinutes($value),
                default => $now->addHour(), // fallback
            };
        }
        return $now->addHour();
    }
}
