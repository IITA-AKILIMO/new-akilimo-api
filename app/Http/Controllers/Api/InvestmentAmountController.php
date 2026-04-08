<?php

namespace App\Http\Controllers\Api;

use App\Http\Concerns\HasPaginationParams;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InvestmentAmountRequest;
use App\Http\Resources\Collections\InvestmentAmountResourceCollection;
use App\Http\Resources\InvestmentAmountResource;
use App\Repositories\InvestmentRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InvestmentAmountController extends Controller
{
    use HasPaginationParams;

    public function __construct(
        protected InvestmentRepo $repo
    ) {}

    public function index(Request $request): InvestmentAmountResourceCollection
    {
        return $this->getPaginatedPrices($request);
    }

    public function byCountry(string $countryCode, Request $request): InvestmentAmountResourceCollection
    {
        $filters = [
            'country' => strtoupper(trim($countryCode)),
            'price_active' => true,
        ];

        return $this->getPaginatedPrices($request, $filters);
    }

    public function store(InvestmentAmountRequest $request): JsonResponse
    {
        $amount = $this->repo->create($request->validated());

        return response()->json([
            'data' => new InvestmentAmountResource($amount),
            'message' => 'Investment amount created.',
        ], 201);
    }

    public function update(InvestmentAmountRequest $request, int $id): JsonResponse
    {
        $amount = $this->repo->update($id, $request->validated());

        return response()->json([
            'data' => new InvestmentAmountResource($amount),
            'message' => 'Investment amount updated.',
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->repo->delete($id);

        return response()->json(null, 204);
    }

    private function getPaginatedPrices(Request $request, array $filters = []): InvestmentAmountResourceCollection
    {
        $perPage = $this->getPerPage($request);
        $orderBy = $this->getOrderBy($request, ['sort_order', 'created_at'], 'sort_order');
        $sort = $this->getSortDirection($request);

        $fertilizerPrices = $this->repo->paginateWithSort(
            perPage: $perPage,
            orderBy: $orderBy,
            direction: $sort,
            filters: $filters
        );

        return InvestmentAmountResourceCollection::make($fertilizerPrices);
    }
}
