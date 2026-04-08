<?php

namespace App\Http\Controllers\Api;

use App\Http\Concerns\HasPaginationParams;
use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\PotatoPriceResourceCollection;
use App\Repositories\PotatoPriceRepo;
use Illuminate\Http\Request;

class PotatoPricesController extends Controller
{
    use HasPaginationParams;

    public function __construct(protected PotatoPriceRepo $repo) {}

    public function index(Request $request): PotatoPriceResourceCollection
    {
        $perPage = $this->getPerPage($request);
        $orderBy = $this->getOrderBy($request, ['sort_order', 'created_at'], 'sort_order');
        $sort = $this->getSortDirection($request);

        return PotatoPriceResourceCollection::make(
            $this->repo->paginateWithSort(perPage: $perPage, orderBy: $orderBy, direction: $sort)
        );
    }

    public function byCountry(string $countryCode, Request $request): PotatoPriceResourceCollection
    {
        $perPage = $this->getPerPage($request);
        $orderBy = $this->getOrderBy($request, ['sort_order', 'created_at'], 'sort_order');
        $sort = $this->getSortDirection($request);

        return PotatoPriceResourceCollection::make(
            $this->repo->paginateWithSort(
                perPage: $perPage,
                orderBy: $orderBy,
                direction: $sort,
                filters: ['country' => strtoupper(trim($countryCode))],
            )
        );
    }
}
