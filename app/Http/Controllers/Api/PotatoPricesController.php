<?php

namespace App\Http\Controllers\Api;

use App\Http\Concerns\HasPaginationParams;
use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\PotatoPriceResourceCollection;
use App\Models\PotatoPrice;
use Illuminate\Http\Request;

class PotatoPricesController extends Controller
{
    use HasPaginationParams;
    public function index(Request $request)
    {
        $perPage = $this->getPerPage($request);
        $orderBy = $this->getOrderBy($request, ['sort_order', 'created_at'], 'sort_order');
        $sort    = $this->getSortDirection($request);

        $cassavaPrices = PotatoPrice::query()
            ->orderBy($orderBy, $sort)
            ->paginate($perPage);

        return PotatoPriceResourceCollection::make($cassavaPrices);
    }

    public function byCountry(string $countryCode, Request $request)
    {
        $perPage = $this->getPerPage($request);
        $orderBy = $this->getOrderBy($request, ['sort_order', 'created_at'], 'sort_order');
        $sort    = $this->getSortDirection($request);

        $cassavaPrices = PotatoPrice::query()
            ->where('country', strtoupper(trim($countryCode)))
            ->orderBy($orderBy, $sort)
            ->paginate($perPage);

        return PotatoPriceResourceCollection::make($cassavaPrices);
    }
}
