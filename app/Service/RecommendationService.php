<?php

namespace App\Service;

use App\Data\ComputeRequestData;
use App\Data\FertilizerData;
use App\Data\PlumberComputeData;
use App\Data\UserInfoData;
use App\Exceptions\RecommendationException;
use App\Repositories\ApiRequestRepo;
use App\Repositories\FertilizerRepo;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class RecommendationService
{
    // Cache TTL as a Carbon interval (e.g. 10 minutes from now)
    protected int|\DateTimeInterface $cacheTTL;

    public function __construct(
        protected FertilizerRepo $fertilizerRepo,
        protected ApiRequestRepo $apiRequestRepo,
        protected PlumberService $plumberService,
    )
    {
        $this->cacheTTL = Carbon::now()->addDays(7);
    }

    /**
     * Handles the entire recommendation computation workflow.
     *
     * @param array $droidRequest The parsed request payload.
     * @return array Contains 'rec_type' and 'recommendation'.
     *
     */
    public function compute(array $droidRequest): array
    {
        $cacheKey = $this->generateCacheKey($droidRequest);

        // Try to get a cached result first
        return Cache::remember($cacheKey, $this->cacheTTL, function () use ($droidRequest) {
            // The existing compute logic (extracted as a private method for clarity)
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

        $availableFertilizers = FertilizerData::collect($this->getAvailableFertilizers($computeRequest->countryCode));
        $requestedFertilizers = FertilizerData::collect($fertilizerList);
        $fertilizerMap = collect($requestedFertilizers)->keyBy('key');

        $computedFertilizers = $this->mapFertilizersToExternalFormat($availableFertilizers, $fertilizerMap);

        $plumberRequest = PlumberComputeData::from($computeRequest, $userInfo, $computedFertilizers);

        $plumberRequest->areaUnit = $this->normalizeAreaUnit($plumberRequest->areaUnit);

        $requestLog = $this->logRequest($deviceToken, $droidRequest, $plumberRequest);

        try {
            $plumberResp = $this->plumberService->sendComputeRequest($plumberRequest);
            $plumberData = Arr::get($plumberResp, 'data', []);

            $this->updateRequestLogResponse($requestLog->id, $plumberData);

            return [
                'rec_type' => Arr::get($plumberData, 'rec_type'),
                'recommendation' => Arr::get($plumberData, 'recommendation'),
            ];
        } catch (RecommendationException $ex) {
            $this->updateRequestLogResponse($requestLog->id, $ex->body);
            throw $ex;
        } catch (Exception $ex) {
            $this->updateRequestLogResponse($requestLog->id, [
                'message' => $ex->getMessage(),
            ]);
            throw $ex;
        }
    }

    /**
     * Generates a unique cache key based on the droidRequest payload.
     *
     * @param array $droidRequest
     * @return string
     */
    private function generateCacheKey(array $droidRequest): string
    {
        // Pick the parts that affect the result, then hash to make the key shorter
        $relevantData = [
            'user_info' => Arr::get($droidRequest, 'user_info', []),
            'compute_request' => Arr::get($droidRequest, 'compute_request', []),
            'fertilizer_list' => Arr::get($droidRequest, 'fertilizer_list', []),
        ];

        return 'recommendation:' . md5(json_encode($relevantData));
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
            default => $areaUnit,
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
}
