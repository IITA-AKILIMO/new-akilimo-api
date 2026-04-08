<?php

namespace App\Http\Controllers\Api;

use App\Http\Concerns\HasPaginationParams;
use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\DefaultPriceResourceCollection;
use App\Http\Resources\Collections\StarchPriceResourceCollection;
use App\Repositories\DefaultPriceRepo;
use App\Repositories\StarchPriceRepo;
use Illuminate\Http\Request;

class DefaultPriceController extends Controller
{
    use HasPaginationParams;

    public function __construct(protected DefaultPriceRepo $repo)
    {
    }

    public function index(Request $request)
    {
        $perPage = $this->getPerPage($request);
        $orderBy = $this->getOrderBy($request, ['created_at'], 'created_at');
        $sort = $this->getSortDirection($request);

        $filters = [
            'country' => $request->input('country')
        ];


        $starchPrices = $this->repo->paginateWithSort(
            perPage: $perPage,
            orderBy: $orderBy,
            direction: $sort,
            filters: $filters);

        return DefaultPriceResourceCollection::make($starchPrices);

    }
}
