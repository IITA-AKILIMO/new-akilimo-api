<?php

namespace App\Http\Controllers\Api;

use App\Http\Concerns\HasPaginationParams;
use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\CassavaUnitResourceCollection;
use App\Repositories\CassavaUnitRepo;
use Illuminate\Http\Request;

class CassavaUnitsController extends Controller
{
    use HasPaginationParams;

    public function __construct(protected CassavaUnitRepo $repo) {}

    public function index(Request $request): CassavaUnitResourceCollection
    {
        $perPage = $this->getPerPage($request);
        $orderBy = $this->getOrderBy($request, ['sort_order', 'created_at'], 'sort_order');
        $sort = $this->getSortDirection($request);

        $cassavaPrices = $this->repo->paginateWithSort(
            perPage: $perPage,
            orderBy: $orderBy,
            direction: $sort,
        );

        return CassavaUnitResourceCollection::make($cassavaPrices);
    }
}
