<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\OperationCostResourceCollection;
use App\Repositories\OperationCostRepo;
use Illuminate\Http\Request;

class OperationCostController extends Controller
{
    public function __construct(protected OperationCostRepo $repo)
    {
    }

    /**
     * @param string $countryCode
     * @param Request $request
     * @return OperationCostResourceCollection
     */
    public function byCountry(string $countryCode, Request $request): OperationCostResourceCollection
    {
        $perPage = $request->input('per_page', 50);
        $orderBy = $request->input('order_by', 'max_cost');
        $operationName = $request->input('operation_name');
        $operationType = $request->input('operation_type');
        $sort = $request->input('sort', 'asc');

        $filters = [
            'country_code' => $countryCode,
            'operation_name' => strtolower(trim($operationName)),
            'operation_type' => strtolower(trim($operationType)),
            'is_active' => true
        ];


        $operationCosts = $this->repo->paginateWithSort(
            perPage: $perPage,
            orderBy: $orderBy,
            direction: $sort,
            filters: $filters);

        return OperationCostResourceCollection::make($operationCosts);
    }
}
