<?php

namespace App\Http\Controllers\Api;

use App\Http\Concerns\HasPaginationParams;
use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\CassavaPriceResourceCollection;
use App\Repositories\CassavaPriceRepo;
use Illuminate\Http\Request;

class CassavaPricesController extends Controller
{
    use HasPaginationParams;
    public function __construct(protected CassavaPriceRepo $repo)
    {
    }

    /**
     * @param Request $request
     * @return CassavaPriceResourceCollection
     */
    public function index(Request $request): CassavaPriceResourceCollection
    {
        $perPage = $this->getPerPage($request);
        $orderBy = $this->getOrderBy($request, ['sort_order', 'created_at'], 'sort_order');
        $sort    = $this->getSortDirection($request);

        $cassavaPrices = $this->repo->paginateWithSort(
            perPage: $perPage,
            orderBy: $orderBy,
            direction: $sort,
        );

        return CassavaPriceResourceCollection::make($cassavaPrices, $this->repo);
    }

    /**
     * @param string $countryCode
     * @param Request $request
     * @return CassavaPriceResourceCollection
     */
    public function byCountry(string $countryCode, Request $request): CassavaPriceResourceCollection
    {
        $perPage = $this->getPerPage($request);
        $orderBy = $this->getOrderBy($request, ['sort_order', 'created_at'], 'sort_order');
        $sort    = $this->getSortDirection($request);

        $filters = [
            'country' => strtoupper(trim($countryCode))
        ];
        $cassavaPrices = $this->repo->paginateWithSort(
            perPage: $perPage,
            orderBy: $orderBy,
            direction: $sort,
            filters: $filters,
        );
        return CassavaPriceResourceCollection::make($cassavaPrices, $this->repo);
    }
}
