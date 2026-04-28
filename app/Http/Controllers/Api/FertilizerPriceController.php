<?php

namespace App\Http\Controllers\Api;

use App\Traits\HasPaginationParams;;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FertilizerPriceRequest;
use App\Http\Resources\Collections\FertilizerPriceResourceCollection;
use App\Http\Resources\FertilizerPriceResource;
use App\Repositories\FertilizerPriceRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FertilizerPriceController extends Controller
{
    use HasPaginationParams;

    public function __construct(
        protected FertilizerPriceRepo $repo
    ) {}

    public function index(Request $request): FertilizerPriceResourceCollection
    {
        return $this->getPaginatedPrices($request);
    }

    public function byCountry(string $countryCode, Request $request): FertilizerPriceResourceCollection
    {
        return $this->getPaginatedPrices($request, [
            'country' => strtoupper(trim($countryCode)),
        ]);
    }

    public function byFertilizerKey(string $fertilizerKey, Request $request): FertilizerPriceResourceCollection
    {
        return $this->getPaginatedPrices($request, [
            'fertilizer_key' => strtoupper(trim($fertilizerKey)),
        ]);
    }

    public function store(FertilizerPriceRequest $request): JsonResponse
    {
        $price = $this->repo->create($request->validated());

        return response()->json([
            'data' => new FertilizerPriceResource($price),
            'message' => 'Fertilizer price created.',
        ], 201);
    }

    public function update(FertilizerPriceRequest $request, int $id): JsonResponse
    {
        $price = $this->repo->update($id, $request->validated());

        return response()->json([
            'data' => new FertilizerPriceResource($price),
            'message' => 'Fertilizer price updated.',
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->repo->delete($id);

        return response()->json(null, 204);
    }

    private function getPaginatedPrices(Request $request, array $filters = []): FertilizerPriceResourceCollection
    {
        $perPage = $this->getPerPage($request);
        $orderBy = $this->getOrderBy($request, ['sort_order', 'created_at'], 'sort_order');
        $sort = $this->getSortDirection($request);

        $fertilizerPrices = $this->repo->paginateWithSort(
            perPage: $perPage,
            orderBy: $orderBy,
            direction: $sort,
            filters: $filters
        );

        return FertilizerPriceResourceCollection::make($fertilizerPrices, $this->repo);
    }
}
