<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\OperationCostResourceCollection;
use App\Models\OperationCost;
use Illuminate\Http\Request;

class OperationCostController extends Controller
{
    public function byCountry(string $countryCode, Request $request)
    {
        $perPage = $request->input('per_page', 50);
        $orderBy = $request->input('order_by', 'max_ngn');
        $operationName = $request->input('operation_name');
        $operationType = $request->input('operation_type');
        $sort = $request->input('sort', 'asc');

        $operationCosts = OperationCost::query()
            ->where('operation_name', strtolower(trim($operationName)))
            ->where('operation_type', strtolower(trim($operationType)))
            ->where('active', true)
            ->orderBy($orderBy, $sort)
            ->paginate($perPage);


        return OperationCostResourceCollection::make($operationCosts);
    }
}
