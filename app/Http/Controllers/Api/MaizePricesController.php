<?php

namespace App\Http\Controllers\Api;

use App\Http\Concerns\HasPaginationParams;
use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\MaizePriceResourceCollection;
use App\Models\MaizePrice;
use Illuminate\Http\Request;

class MaizePricesController extends Controller
{
    use HasPaginationParams;
    /**
     * @param Request $request
     * @return MaizePriceResourceCollection
     */
    public function index(Request $request)
    {
        $perPage = $this->getPerPage($request);
        $orderBy = $this->getOrderBy($request, ['sort_order', 'created_at'], 'sort_order');
        $sort    = $this->getSortDirection($request);

        $maizePrices = MaizePrice::query()
            ->orderBy($orderBy, $sort)
            ->paginate($perPage);

        return MaizePriceResourceCollection::make($maizePrices);
    }

    /**
     * @param string $countryCode
     * @param Request $request
     * @return MaizePriceResourceCollection
     */
    public function byCountry(string $countryCode, Request $request)
    {
        $perPage = $this->getPerPage($request);
        $orderBy = $this->getOrderBy($request, ['sort_order', 'created_at'], 'sort_order');
        $sort    = $this->getSortDirection($request);

        $maizePrices = MaizePrice::query()
            ->where('country', strtoupper(trim($countryCode)))
            ->orderBy($orderBy, $sort)
            ->paginate($perPage);

        return MaizePriceResourceCollection::make($maizePrices);
    }
}
