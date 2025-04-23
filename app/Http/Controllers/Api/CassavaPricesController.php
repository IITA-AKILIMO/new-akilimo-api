<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\CassavaPriceResourceCollection;
use App\Http\Resources\Collections\StarchFactoryResourceCollection;
use App\Models\CassavaPrice;
use App\Models\StarchFactory;
use Illuminate\Http\Request;

class CassavaPricesController extends Controller
{
    public function byCountry(string $countryCode, Request $request)
    {
        $perPage = $request->input('per_page', 50); // Number of records per page, default is 50
        $orderBy = $request->input('order_by', 'sort_order'); // Default order by invoice_date
        $sort = $request->input('sort', 'asc'); // Default sort order is ascending

        $cassavaPrices = CassavaPrice::query()
            ->where('country', strtoupper(trim($countryCode)))
            ->where('price_active', true)
            ->orderBy($orderBy, $sort)
            ->paginate($perPage);

        return CassavaPriceResourceCollection::make($cassavaPrices);
    }
}
