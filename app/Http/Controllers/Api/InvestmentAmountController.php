<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\InvestmentAmountResourceCollection;
use App\Repositories\InvestmentRepo;
use Illuminate\Http\Request;

class InvestmentAmountController extends Controller
{

    public function __construct(
        protected InvestmentRepo $repo
    )
    {
    }

    public function index(Request $request): InvestmentAmountResourceCollection
    {
        return $this->getPaginatedPrices($request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function byCountry(string $countryCode, Request $request): InvestmentAmountResourceCollection
    {
        $filters = [
            'country' => strtoupper(trim($countryCode)),
            'price_active' => true,
        ];

        return $this->getPaginatedPrices($request, $filters);
    }

    /**
     * Shared logic for paginating and sorting fertilizer prices.
     */
    private function getPaginatedPrices(Request $request, array $filters = []): InvestmentAmountResourceCollection
    {
        $perPage = (int)$request->input('per_page', 50);
        $orderBy = $request->input('order_by', 'sort_order');
        $sort = $request->input('sort', 'asc');

        $fertilizerPrices = $this->repo->paginateWithSort(
            perPage: $perPage,
            orderBy: $orderBy,
            direction: $sort,
            filters: $filters
        );

        return InvestmentAmountResourceCollection::make($fertilizerPrices);
    }
}
