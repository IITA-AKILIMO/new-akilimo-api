<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\CurrencyResourceCollection;
use App\Repositories\CurrencyRepo;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{

    public function __construct(protected CurrencyRepo $repo)
    {
    }

    /**
     * @param Request $request
     * @return CurrencyResourceCollection
     */
    public function index(Request $request): CurrencyResourceCollection
    {
        $perPage = $request->input('per_page', 50); // Number of records per page, default is 50
        $orderBy = $request->input('order_by', 'currency_code'); // Default order by invoice_date
        $sort = $request->input('sort', 'asc'); // Default sort order is ascending

        $currencies = $this->repo->paginateWithSort(
            perPage: $perPage,
            orderBy: $orderBy,
            direction: $sort,
        );

        return CurrencyResourceCollection::make($currencies);
    }
}
