<?php

namespace App\Http\Controllers\Api;

use App\Http\Concerns\HasPaginationParams;
use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\StarchFactoryResourceCollection;
use App\Repositories\StarchFactoryRepo;
use Illuminate\Http\Request;

class StarchFactoryController extends Controller
{
    use HasPaginationParams;

    public function __construct(protected StarchFactoryRepo $repo) {}

    public function index(Request $request): StarchFactoryResourceCollection
    {
        $perPage = $this->getPerPage($request);
        $orderBy = $this->getOrderBy($request, ['sort_order', 'name', 'created_at'], 'sort_order');
        $sort = $this->getSortDirection($request);

        $starchFactory = $this->repo->paginateWithSort(
            perPage: $perPage,
            orderBy: $orderBy,
            direction: $sort);

        return StarchFactoryResourceCollection::make($starchFactory);
    }

    public function byCountry(string $countryCode, Request $request): StarchFactoryResourceCollection
    {
        $perPage = $this->getPerPage($request);
        $orderBy = $this->getOrderBy($request, ['sort_order', 'name', 'created_at'], 'sort_order');
        $sort = $this->getSortDirection($request);

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
