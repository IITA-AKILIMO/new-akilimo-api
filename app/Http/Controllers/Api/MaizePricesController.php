<?php

namespace App\Http\Controllers\Api;

use App\Http\Concerns\HasPaginationParams;
use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\MaizePriceResourceCollection;
use App\Repositories\MaizePriceRepo;
use Illuminate\Http\Request;

class MaizePricesController extends Controller
{
    use HasPaginationParams;

    public function __construct(protected MaizePriceRepo $repo) {}

    public function index(Request $request): MaizePriceResourceCollection
    {
        $perPage = $this->getPerPage($request);
        $orderBy = $this->getOrderBy($request, ['sort_order', 'created_at'], 'sort_order');
        $sort = $this->getSortDirection($request);

        return MaizePriceResourceCollection::make(
            $this->repo->paginateWithSort(perPage: $perPage, orderBy: $orderBy, direction: $sort),
            $this->repo,
        );
    }

    public function byCountry(string $countryCode, Request $request): MaizePriceResourceCollection
    {
        $perPage = $this->getPerPage($request);
        $orderBy = $this->getOrderBy($request, ['sort_order', 'created_at'], 'sort_order');
        $sort = $this->getSortDirection($request);

        return MaizePriceResourceCollection::make(
            $this->repo->paginateWithSort(
                perPage: $perPage,
                orderBy: $orderBy,
                direction: $sort,
                filters: ['country' => strtoupper(trim($countryCode))],
            ),
            $this->repo,
        );
    }
}
