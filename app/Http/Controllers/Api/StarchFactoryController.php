<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\StarchFactoryResourceCollection;
use App\Http\Resources\StarchFactoryResource;
use App\Models\StarchFactory;
use Illuminate\Http\Request;

class StarchFactoryController extends Controller
{

    public function byCountry(string $countryCode, Request $request)
    {
        $perPage = $request->input('per_page', 50); // Number of records per page, default is 50
        $orderBy = $request->input('order_by', 'sort_order'); // Default order by invoice_date
        $sort = $request->input('sort', 'asc'); // Default sort order is ascending

        $starchFactory = StarchFactory::query()
            ->where('country', strtoupper(trim($countryCode)))
            ->where('factory_active', true)
            ->orderBy($orderBy, $sort)
            ->paginate($perPage);

        return StarchFactoryResourceCollection::make($starchFactory);
    }
}
