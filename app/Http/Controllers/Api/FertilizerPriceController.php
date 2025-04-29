<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\FertilizerPriceResourceCollection;
use App\Repositories\FertilizerPriceRepo;
use Illuminate\Http\Request;

class FertilizerPriceController extends Controller
{

    public function __construct(protected FertilizerPriceRepo $repo)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): FertilizerPriceResourceCollection
    {
        $perPage = $request->input('per_page', 50); // Number of records per page, default is 50
        $orderBy = $request->input('order_by', 'sort_order'); // Default order by invoice_date
        $sort = $request->input('sort', 'asc'); // Default sort order is ascending

        $fertilizerPrices = $this->repo->paginateWithSort(
            perPage: $perPage,
            sortBy: $orderBy,
            direction: $sort);

        return FertilizerPriceResourceCollection::make($fertilizerPrices);
    }

    /**
     * @param string $countryCode
     * @param Request $request
     * @return FertilizerPriceResourceCollection
     */
    public function byCountry(string $countryCode, Request $request): FertilizerPriceResourceCollection
    {
        $perPage = $request->input('per_page', 50);
        $orderBy = $request->input('order_by', 'sort_order');
        $sort = $request->input('sort', 'asc');

        $filters = [
            'country' => strtoupper(trim($countryCode)),
            'price_active' => true
        ];


        $fertilizerPrices = $this->repo->paginateWithSort(
            perPage: $perPage,
            sortBy: $orderBy,
            direction: $sort,
            filters: $filters);

        return FertilizerPriceResourceCollection::make($fertilizerPrices);
    }

    public function byFertilizerKey(string $fertilizerKey, Request $request): FertilizerPriceResourceCollection
    {
        $perPage = $request->input('per_page', 50); // Number of records per page, default is 50
        $orderBy = $request->input('order_by', 'sort_order'); // Default order by invoice_date
        $sort = $request->input('sort', 'asc'); // Default sort order is ascending

        $filters = [
            'fertilizer_key' => strtoupper(trim($fertilizerKey)),
            'price_active' => true
        ];

        $fertilizerPrices = $this->repo->paginateWithSort(
            perPage: $perPage,
            sortBy: $orderBy,
            direction: $sort,
            filters: $filters);

        return FertilizerPriceResourceCollection::make($fertilizerPrices);
    }

}
