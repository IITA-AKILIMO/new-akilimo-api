<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\PotatoPriceResourceCollection;
use App\Models\PotatoPrice;
use Illuminate\Http\Request;

class PotatoPricesController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 50); // Number of records per page, default is 50
        $orderBy = $request->input('order_by', 'sort_order'); // Default order by invoice_date
        $sort = $request->input('sort', 'asc'); // Default sort order is ascending

        $cassavaPrices = PotatoPrice::query()
            ->where('price_active', true)
            ->orderBy($orderBy, $sort)
            ->paginate($perPage);

        return PotatoPriceResourceCollection::make($cassavaPrices);
    }

    public function byCountry(string $countryCode, Request $request)
    {
        $perPage = $request->input('per_page', 50); // Number of records per page, default is 50
        $orderBy = $request->input('order_by', 'sort_order'); // Default order by invoice_date
        $sort = $request->input('sort', 'asc'); // Default sort order is ascending

        $cassavaPrices = PotatoPrice::query()
            ->where('country', strtoupper(trim($countryCode)))
            ->where('price_active', true)
            ->orderBy($orderBy, $sort)
            ->paginate($perPage);

        return PotatoPriceResourceCollection::make($cassavaPrices);
    }
}
