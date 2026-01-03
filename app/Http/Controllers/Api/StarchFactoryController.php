<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\StarchFactoryResourceCollection;
use App\Repositories\StarchFactoryRepo;
use Illuminate\Http\Request;

class StarchFactoryController extends Controller
{
    public function __construct(protected StarchFactoryRepo $repo)
    {
    }

    /**
     * @param Request $request
     * @return StarchFactoryResourceCollection
     */
    public function index(Request $request): StarchFactoryResourceCollection
    {
        $perPage = $request->input('per_page', 50); // Number of records per page, default is 50
        $orderBy = $request->input('order_by', 'sort_order'); // Default order by invoice_date
        $sort = $request->input('sort', 'asc'); // Default sort order is ascending


        $starchFactory = $this->repo->paginateWithSort(
            perPage: $perPage,
            orderBy: $orderBy,
            direction: $sort);

        return StarchFactoryResourceCollection::make($starchFactory);
    }

    /**
     * @param string $countryCode
     * @param Request $request
     * @return StarchFactoryResourceCollection
     */
    public function byCountry(string $countryCode, Request $request): StarchFactoryResourceCollection
    {
        $perPage = $request->input('per_page', 50); // Number of records per page, default is 50
        $orderBy = $request->input('order_by', 'sort_order'); // Default order by invoice_date
        $sort = $request->input('sort', 'asc'); // Default sort order is ascending

        $filters = [
            'country' => strtoupper(trim($countryCode)),
        ];

        $starchFactory = $this->repo->paginateWithSort(
            perPage: $perPage,
            orderBy: $orderBy,
            direction: $sort,
            filters: $filters);

        return StarchFactoryResourceCollection::make($starchFactory);
    }
}
