<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\CassavaPriceResourceCollection;
use App\Repositories\CassavaPriceRepo;
use Illuminate\Http\Request;

class CassavaPricesController extends Controller
{
    public function __construct(protected CassavaPriceRepo $repo)
    {
    }

    /**
     * @param Request $request
     * @return CassavaPriceResourceCollection
     */
    public function index(Request $request): CassavaPriceResourceCollection
    {
        $perPage = $request->input('per_page', 50); // Number of records per page, default is 50
        $orderBy = $request->input('order_by', 'sort_order'); // Default order by invoice_date
        $sort = $request->input('sort', 'asc'); // Default sort order is ascending

        $cassavaPrices = $this->repo->paginateWithSort(
            perPage: $perPage,
            orderBy: $orderBy,
            direction: $sort,
        );

        return CassavaPriceResourceCollection::make($cassavaPrices);
    }

    /**
     * @param string $countryCode
     * @param Request $request
     * @return CassavaPriceResourceCollection
     */
    public function byCountry(string $countryCode, Request $request): CassavaPriceResourceCollection
    {
        $perPage = $request->input('per_page', 50); // Number of records per page, default is 50
        $orderBy = $request->input('order_by', 'sort_order'); // Default order by invoice_date
        $sort = $request->input('sort', 'asc'); // Default sort order is ascending

        $filters = [
            'country' => strtoupper(trim($countryCode))
        ];
        $cassavaPrices = $this->repo->paginateWithSort(
            perPage: $perPage,
            orderBy: $orderBy,
            direction: $sort,
            filters: $filters,
        );
        return CassavaPriceResourceCollection::make($cassavaPrices);
    }
}
