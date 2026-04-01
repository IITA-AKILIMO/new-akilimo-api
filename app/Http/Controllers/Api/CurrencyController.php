<?php

namespace App\Http\Controllers\Api;

use App\Http\Concerns\HasPaginationParams;
use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\CurrencyResourceCollection;
use App\Repositories\CurrencyRepo;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    use HasPaginationParams;

    public function __construct(protected CurrencyRepo $repo)
    {
    }

    /**
     * @param Request $request
     * @return CurrencyResourceCollection
     */
    public function index(Request $request): CurrencyResourceCollection
    {
        $perPage = $this->getPerPage($request);
        $orderBy = $this->getOrderBy($request, ['sort_order', 'currency_code', 'created_at'], 'currency_code');
        $sort    = $this->getSortDirection($request);

        $currencies = $this->repo->paginateWithSort(
            perPage: $perPage,
            orderBy: $orderBy,
            direction: $sort,
        );

        return CurrencyResourceCollection::make($currencies);
    }
}
