<?php

namespace App\Http\Controllers\Api;

use App\Http\Concerns\HasPaginationParams;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StarchPriceRequest;
use App\Http\Resources\Collections\StarchPriceResourceCollection;
use App\Http\Resources\StarchPriceResource;
use App\Repositories\StarchPriceRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StarchPricesController extends Controller
{
    use HasPaginationParams;

    public function __construct(protected StarchPriceRepo $repo) {}

    public function index(Request $request): StarchPriceResourceCollection
    {
        $perPage = $this->getPerPage($request);
        $orderBy = $this->getOrderBy($request, ['created_at'], 'created_at');
        $sort = $this->getSortDirection($request);

        $relationFilters = [
            'starch_factory' => ['country' => $request->input('country')],
        ];

        $starchPrices = $this->repo->paginateWithSort(
            perPage: $perPage,
            orderBy: $orderBy,
            direction: $sort,
            with: ['starch_factory'],
            relationFilters: $relationFilters);

        return StarchPriceResourceCollection::make($starchPrices);
    }

    public function store(StarchPriceRequest $request): JsonResponse
    {
        $price = $this->repo->create($request->validated());

        return response()->json([
            'data' => new StarchPriceResource($price->load('starch_factory')),
            'message' => 'Starch price created.',
        ], 201);
    }

    public function update(StarchPriceRequest $request, int $id): JsonResponse
    {
        $price = $this->repo->update($id, $request->validated());

        return response()->json([
            'data' => new StarchPriceResource($price->load('starch_factory')),
            'message' => 'Starch price updated.',
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->repo->delete($id);

        return response()->json(null, 204);
    }
}
