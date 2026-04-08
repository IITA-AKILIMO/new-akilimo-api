<?php

namespace App\Http\Controllers\Api;

use App\Http\Concerns\HasPaginationParams;
use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\OperationCostResourceCollection;
use App\Repositories\OperationCostRepo;
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
}
