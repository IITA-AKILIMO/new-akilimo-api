<?php

namespace App\Http\Controllers\Api;

use App\Http\Concerns\HasPaginationParams;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PotatoPriceRequest;
use App\Http\Resources\Collections\PotatoPriceResourceCollection;
use App\Http\Resources\PotatoPriceResource;
use App\Repositories\PotatoPriceRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PotatoPricesController extends Controller
{
    use HasPaginationParams;

    public function __construct(protected PotatoPriceRepo $repo) {}

    public function index(Request $request): PotatoPriceResourceCollection
    {
        $perPage = $this->getPerPage($request);
        $orderBy = $this->getOrderBy($request, ['sort_order', 'created_at'], 'sort_order');
        $sort = $this->getSortDirection($request);

        return PotatoPriceResourceCollection::make(
            $this->repo->paginateWithSort(perPage: $perPage, orderBy: $orderBy, direction: $sort)
        );
    }

    public function byCountry(string $countryCode, Request $request): PotatoPriceResourceCollection
    {
        $perPage = $this->getPerPage($request);
        $orderBy = $this->getOrderBy($request, ['sort_order', 'created_at'], 'sort_order');
        $sort = $this->getSortDirection($request);

        return PotatoPriceResourceCollection::make(
            $this->repo->paginateWithSort(
                perPage: $perPage,
                orderBy: $orderBy,
                direction: $sort,
                filters: ['country' => strtoupper(trim($countryCode))],
            )
        );
    }

    public function store(PotatoPriceRequest $request): JsonResponse
    {
        $price = $this->repo->create($request->validated());

        return response()->json([
            'data' => new PotatoPriceResource($price),
            'message' => 'Potato price created.',
        ], 201);
    }

    public function update(PotatoPriceRequest $request, int $id): JsonResponse
    {
        $price = $this->repo->update($id, $request->validated());

        return response()->json([
            'data' => new PotatoPriceResource($price),
            'message' => 'Potato price updated.',
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->repo->delete($id);

        return response()->json(null, 204);
    }
}
