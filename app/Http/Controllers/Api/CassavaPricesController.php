<?php

namespace App\Http\Controllers\Api;

use App\Http\Concerns\HasPaginationParams;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CassavaPriceRequest;
use App\Http\Resources\CassavaPriceResource;
use App\Http\Resources\Collections\CassavaPriceResourceCollection;
use App\Repositories\CassavaPriceRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CassavaPricesController extends Controller
{
    use HasPaginationParams;

    public function __construct(protected CassavaPriceRepo $repo) {}

    public function index(Request $request): CassavaPriceResourceCollection
    {
        $perPage = $this->getPerPage($request);
        $orderBy = $this->getOrderBy($request, ['sort_order', 'created_at'], 'sort_order');
        $sort = $this->getSortDirection($request);

        $cassavaPrices = $this->repo->paginateWithSort(
            perPage: $perPage,
            orderBy: $orderBy,
            direction: $sort,
        );

        return CassavaPriceResourceCollection::make($cassavaPrices, $this->repo);
    }

    public function byCountry(string $countryCode, Request $request): CassavaPriceResourceCollection
    {
        $perPage = $this->getPerPage($request);
        $orderBy = $this->getOrderBy($request, ['sort_order', 'created_at'], 'sort_order');
        $sort = $this->getSortDirection($request);

        $filters = [
            'country' => strtoupper(trim($countryCode)),
        ];
        $cassavaPrices = $this->repo->paginateWithSort(
            perPage: $perPage,
            orderBy: $orderBy,
            direction: $sort,
            filters: $filters,
        );

        return CassavaPriceResourceCollection::make($cassavaPrices, $this->repo);
    }

    public function store(CassavaPriceRequest $request): JsonResponse
    {
        $price = $this->repo->create($request->validated());

        return response()->json([
            'data' => new CassavaPriceResource($price),
            'message' => 'Cassava price created.',
        ], 201);
    }

    public function update(CassavaPriceRequest $request, int $id): JsonResponse
    {
        $price = $this->repo->update($id, $request->validated());

        return response()->json([
            'data' => new CassavaPriceResource($price),
            'message' => 'Cassava price updated.',
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->repo->delete($id);

        return response()->json(null, 204);
    }
}
