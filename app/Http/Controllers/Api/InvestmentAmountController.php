<?php

namespace App\Http\Controllers\Api;

use App\Http\Concerns\HasPaginationParams;
use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\InvestmentAmountResourceCollection;
use App\Repositories\InvestmentRepo;
use Illuminate\Http\Request;

class InvestmentAmountController extends Controller
{
    use HasPaginationParams;

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
        $perPage = $this->getPerPage($request);
        $orderBy = $this->getOrderBy($request, ['sort_order', 'created_at'], 'sort_order');
        $sort    = $this->getSortDirection($request);

        $fertilizerPrices = $this->repo->paginateWithSort(
            perPage: $perPage,
            orderBy: $orderBy,
            direction: $sort,
            filters: $filters
        );

        return InvestmentAmountResourceCollection::make($fertilizerPrices);
    }
}
