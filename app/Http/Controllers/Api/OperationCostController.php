<?php

namespace App\Http\Controllers\Api;

use App\Traits\HasPaginationParams;;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OperationCostRequest;
use App\Http\Resources\Collections\OperationCostResourceCollection;
use App\Http\Resources\OperationCostResource;
use App\Repositories\OperationCostRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OperationCostController extends Controller
{
    use HasPaginationParams;

    public function __construct(protected OperationCostRepo $repo) {}

    public function index(Request $request): OperationCostResourceCollection
    {
        $perPage = $this->getPerPage($request);
        $orderBy = $this->getOrderBy($request, ['sort_order', 'max_cost', 'min_cost', 'created_at'], 'max_cost');
        $sort = $this->getSortDirection($request);
        $operationName = $request->input('operation_name');
        $operationType = $request->input('operation_type');

        $filters = array_filter([
            'operation_name' => filled($operationName) ? strtolower(trim($operationName)) : null,
            'operation_type' => filled($operationType) ? strtolower(trim($operationType)) : null,
        ]);

        $operationCosts = $this->repo->paginateWithSort(
            perPage: $perPage,
            orderBy: $orderBy,
            direction: $sort,
            filters: $filters);

        return OperationCostResourceCollection::make($operationCosts);
    }

    public function byCountry(string $countryCode, Request $request): OperationCostResourceCollection
    {
        $perPage = $this->getPerPage($request);
        $orderBy = $this->getOrderBy($request, ['sort_order', 'max_cost', 'min_cost', 'created_at'], 'max_cost');
        $sort = $this->getSortDirection($request);
        $operationName = $request->input('operation_name');
        $operationType = $request->input('operation_type');

        $filters = array_filter([
            'country_code' => $countryCode,
            'operation_name' => filled($operationName) ? strtolower(trim($operationName)) : null,
            'operation_type' => filled($operationType) ? strtolower(trim($operationType)) : null,
        ]);

        $operationCosts = $this->repo->paginateWithSort(
            perPage: $perPage,
            orderBy: $orderBy,
            direction: $sort,
            filters: $filters);

        return OperationCostResourceCollection::make($operationCosts);
    }

    public function store(OperationCostRequest $request): JsonResponse
    {
        $cost = $this->repo->create($request->validated());

        return response()->json([
            'data' => new OperationCostResource($cost),
            'message' => 'Operation cost created.',
        ], 201);
    }

    public function update(OperationCostRequest $request, int $id): JsonResponse
    {
        $cost = $this->repo->update($id, $request->validated());

        return response()->json([
            'data' => new OperationCostResource($cost),
            'message' => 'Operation cost updated.',
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->repo->delete($id);

        return response()->json(null, 204);
    }
}
