<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CurrencyRequest;
use App\Http\Resources\Collections\CurrencyResourceCollection;
use App\Http\Resources\CurrencyResource;
use App\Repositories\CurrencyRepo;
use App\Traits\HasPaginationParams;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    use HasPaginationParams;

    public function __construct(protected CurrencyRepo $repo)
    {
    }

    public function index(Request $request): CurrencyResourceCollection
    {
        $perPage = $this->getPerPage($request);
        $orderBy = $this->getOrderBy($request, ['sort_order', 'currency_code', 'created_at'], 'currency_code');
        $sort = $this->getSortDirection($request);

        $currencies = $this->repo->paginateWithSort(
            perPage: $perPage,
            orderBy: $orderBy,
            direction: $sort,
        );

        return CurrencyResourceCollection::make($currencies);
    }

    public function store(CurrencyRequest $request): JsonResponse
    {
        $currency = $this->repo->create($request->validated());

        return response()->json([
            'data' => new CurrencyResource($currency),
            'message' => 'Currency created.',
        ], 201);
    }

    public function update(CurrencyRequest $request, int $id): JsonResponse
    {
        $currency = $this->repo->update($id, $request->validated());

        return response()->json([
            'data' => new CurrencyResource($currency),
            'message' => 'Currency updated.',
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->repo->delete($id);

        return response()->json(null, 204);
    }
}
