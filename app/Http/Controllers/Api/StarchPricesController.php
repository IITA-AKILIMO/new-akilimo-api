<?php

namespace App\Http\Controllers\Api;

use App\Http\Concerns\HasPaginationParams;
use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\StarchPriceResourceCollection;
use App\Models\StarchPrice;
use App\Repositories\StarchFactoryRepo;
use App\Repositories\StarchPriceRepo;
use Illuminate\Http\Request;

class StarchPricesController extends Controller
{
    use HasPaginationParams;

    public function __construct(protected StarchPriceRepo $repo)
    {
    }

    /**
     * @param Request $request
     * @return StarchPriceResourceCollection
     */
    public function index(Request $request): StarchPriceResourceCollection
    {
        $perPage = $this->getPerPage($request);
        $orderBy = $this->getOrderBy($request, ['created_at'], 'created_at');
        $sort = $this->getSortDirection($request);

        $relationFilters = [
            'starch_factory' => ['country' => $request->input('country')]
        ];


        $starchPrices = $this->repo->paginateWithSort(
            perPage: $perPage,
            orderBy: $orderBy,
            direction: $sort,
            with: ['starch_factory'],
            relationFilters: $relationFilters);

        return StarchPriceResourceCollection::make($starchPrices);

    }
}
