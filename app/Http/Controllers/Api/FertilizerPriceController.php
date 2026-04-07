<?php

namespace App\Http\Controllers\Api;

use App\Http\Concerns\HasPaginationParams;
use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\FertilizerPriceResourceCollection;
use App\Repositories\FertilizerPriceRepo;
use Illuminate\Http\Request;

class FertilizerPriceController extends Controller
{
    use HasPaginationParams;
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
            'country' => strtoupper(trim($countryCode))
        ]);
    }

    /**
     * Display a paginated list of fertilizer prices filtered by fertilizer key.
     */
    public function byFertilizerKey(string $fertilizerKey, Request $request): FertilizerPriceResourceCollection
    {
        return $this->getPaginatedPrices($request, [
            'fertilizer_key' => strtoupper(trim($fertilizerKey))
        ]);
    }

    /**
     * Shared logic for paginating and sorting fertilizer prices.
     */
    private function getPaginatedPrices(Request $request, array $filters = []): FertilizerPriceResourceCollection
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

        return FertilizerPriceResourceCollection::make($fertilizerPrices, $this->repo);
    }
}
