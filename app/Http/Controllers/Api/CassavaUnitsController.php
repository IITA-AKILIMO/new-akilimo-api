<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\CassavaUnitResourceCollection;
use App\Repositories\CassavaUnitRepo;
use Illuminate\Http\Request;

class CassavaUnitsController extends Controller
{
    public function __construct(protected CassavaUnitRepo $repo)
    {
    }

    /**
     * @param Request $request
     * @return CassavaUnitResourceCollection
     */
    public function index(Request $request): CassavaUnitResourceCollection
    {
        $perPage = $request->input('per_page', 50); // Number of records per page, default is 50
        $orderBy = $request->input('order_by', 'sort_order'); // Default order by invoice_date
        $sort = $request->input('sort', 'asc'); // Default sort order is ascending

        $cassavaPrices = $this->repo->paginateWithSort(
            perPage: $perPage,
            orderBy: $orderBy,
            direction: $sort,
        );

        return CassavaUnitResourceCollection::make($cassavaPrices);
    }
}
