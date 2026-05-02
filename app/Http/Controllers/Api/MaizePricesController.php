<?php

namespace App\Http\Controllers\Api;

use App\Traits\HasPaginationParams;;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MaizePriceRequest;
use App\Http\Resources\Collections\MaizePriceResourceCollection;
use App\Http\Resources\MaizePriceResource;
use App\Repositories\MaizePriceRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MaizePricesController extends Controller
{
    use HasPaginationParams;

    public function __construct(protected MaizePriceRepo $repo) {}

    public function index(Request $request): MaizePriceResourceCollection
    {
        $perPage = $this->getPerPage($request);
        $orderBy = $this->getOrderBy($request, ['sort_order', 'created_at'], 'sort_order');
        $sort = $this->getSortDirection($request);

        return MaizePriceResourceCollection::make(
            $this->repo->paginateWithSort(perPage: $perPage, orderBy: $orderBy, direction: $sort),
            $this->repo,
        );
    }

    public function byCountry(string $countryCode, Request $request): MaizePriceResourceCollection
    {
        $perPage = $this->getPerPage($request);
        $orderBy = $this->getOrderBy($request, ['sort_order', 'created_at'], 'sort_order');
        $sort = $this->getSortDirection($request);

        return MaizePriceResourceCollection::make(
            $this->repo->paginateWithSort(
                perPage: $perPage,
                orderBy: $orderBy,
                direction: $sort,
                filters: ['country' => strtoupper(trim($countryCode))],
            ),
            $this->repo,
        );
    }

    public function store(MaizePriceRequest $request): JsonResponse
    {
        $price = $this->repo->create($request->validated());

        return response()->json([
            'data' => new MaizePriceResource($price),
            'message' => 'Maize price created.',
        ], 201);
    }

    public function update(MaizePriceRequest $request, int $id): JsonResponse
    {
        $price = $this->repo->update($id, $request->validated());

        return response()->json([
            'data' => new MaizePriceResource($price),
            'message' => 'Maize price updated.',
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->repo->delete($id);

        return response()->json(null, 204);
    }
}
