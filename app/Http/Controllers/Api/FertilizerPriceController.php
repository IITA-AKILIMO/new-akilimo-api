<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\FertilizerPriceResourceCollection;
use App\Repositories\FertilizerPriceRepo;
use Illuminate\Http\Request;

class FertilizerPriceController extends Controller
{
    public function __construct(
        protected FertilizerPriceRepo $repo
    ) {}

    /**
     * Display a paginated list of all fertilizer prices.
     */
    public function index(Request $request): FertilizerPriceResourceCollection
    {
        return $this->getPaginatedPrices($request);
    }

    /**
     * Display a paginated list of fertilizer prices filtered by country.
     */
    public function byCountry(string $countryCode, Request $request): FertilizerPriceResourceCollection
    {
        return $this->getPaginatedPrices($request, [
            'country' => strtoupper(trim($countryCode)),
            'price_active' => true,
        ]);
    }

    /**
     * Display a paginated list of fertilizer prices filtered by fertilizer key.
     */
    public function byFertilizerKey(string $fertilizerKey, Request $request): FertilizerPriceResourceCollection
    {
        return $this->getPaginatedPrices($request, [
            'fertilizer_key' => strtoupper(trim($fertilizerKey)),
            'price_active' => true,
        ]);
    }

    /**
     * Shared logic for paginating and sorting fertilizer prices.
     */
    private function getPaginatedPrices(Request $request, array $filters = []): FertilizerPriceResourceCollection
    {
        $perPage = (int) $request->input('per_page', 50);
        $orderBy = $request->input('order_by', 'sort_order');
        $sort = $request->input('sort', 'asc');

        $fertilizerPrices = $this->repo->paginateWithSort(
            perPage: $perPage,
            sortBy: $orderBy,
            direction: $sort,
            filters: $filters
        );

        return FertilizerPriceResourceCollection::make($fertilizerPrices);
    }
}
