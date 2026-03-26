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
    public function __construct(protected OperationCostRepo $repo)
    {
    }

    /**
     * @param Request $request
     * @return OperationCostResourceCollection
     */
    public function index(Request $request): OperationCostResourceCollection
    {
        $perPage       = $this->getPerPage($request);
        $orderBy       = $this->getOrderBy($request, ['sort_order', 'max_cost', 'min_cost', 'created_at'], 'max_cost');
        $sort          = $this->getSortDirection($request);
        $operationName = $request->input('operation_name');
        $operationType = $request->input('operation_type');

        $filters = [
            'operation_name' => strtolower(trim($operationName)),
            'operation_type' => strtolower(trim($operationType))
        ];


        $operationCosts = $this->repo->paginateWithSort(
            perPage: $perPage,
            orderBy: $orderBy,
            direction: $sort,
            filters: $filters);

        return OperationCostResourceCollection::make($operationCosts);
    }

    /**
     * @param string $countryCode
     * @param Request $request
     * @return OperationCostResourceCollection
     */
    public function byCountry(string $countryCode, Request $request): OperationCostResourceCollection
    {
        $perPage       = $this->getPerPage($request);
        $orderBy       = $this->getOrderBy($request, ['sort_order', 'max_cost', 'min_cost', 'created_at'], 'max_cost');
        $sort          = $this->getSortDirection($request);
        $operationName = $request->input('operation_name');
        $operationType = $request->input('operation_type');

        $filters = [
            'country_code' => $countryCode,
            'operation_name' => strtolower(trim($operationName)),
            'operation_type' => strtolower(trim($operationType))
        ];


        $operationCosts = $this->repo->paginateWithSort(
            perPage: $perPage,
            orderBy: $orderBy,
            direction: $sort,
            filters: $filters);

        return OperationCostResourceCollection::make($operationCosts);
    }
}
