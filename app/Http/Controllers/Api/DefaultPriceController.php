<?php

namespace App\Http\Controllers\Api;

use App\Traits\HasPaginationParams;;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DefaultPriceRequest;
use App\Http\Resources\Collections\DefaultPriceResourceCollection;
use App\Http\Resources\DefaultPriceResource;
use App\Repositories\DefaultPriceRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DefaultPriceController extends Controller
{
    use HasPaginationParams;

    public function __construct(protected DefaultPriceRepo $repo) {}

    public function index(Request $request)
    {
        $perPage = $this->getPerPage($request);
        $orderBy = $this->getOrderBy($request, ['created_at'], 'created_at');
        $sort = $this->getSortDirection($request);

        $filters = [
            'country' => $request->input('country'),
        ];

        $defaultPrices = $this->repo->paginateWithSort(
            perPage: $perPage,
            orderBy: $orderBy,
            direction: $sort,
            filters: $filters);

        return DefaultPriceResourceCollection::make($defaultPrices);
    }

    public function store(DefaultPriceRequest $request): JsonResponse
    {
        $price = $this->repo->create($request->validated());

        return response()->json([
            'data' => new DefaultPriceResource($price),
            'message' => 'Default price created.',
        ], 201);
    }

    public function update(DefaultPriceRequest $request, int $id): JsonResponse
    {
        $price = $this->repo->update($id, $request->validated());

        return response()->json([
            'data' => new DefaultPriceResource($price),
            'message' => 'Default price updated.',
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->repo->delete($id);

        return response()->json(null, 204);
    }
}
