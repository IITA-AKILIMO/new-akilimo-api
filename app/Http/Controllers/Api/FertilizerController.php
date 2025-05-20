<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\FertilizerResourceCollection;
use App\Models\Fertilizer;
use Illuminate\Http\Request;

class FertilizerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 50); // Number of records per page, default is 50
        $country = $request->input('country_code');
        $orderBy = $request->input('order_by', 'sort_order'); // Default order by invoice_date
        $sort = $request->input('sort', 'asc'); // Default sort order is ascending

        $currencies = Fertilizer::query()
            ->where('country', strtoupper(trim($country)))
            ->where('available', true)
            ->orderBy($orderBy, $sort)
            ->paginate($perPage);

        return FertilizerResourceCollection::make($currencies);
    }

    /**
     * @param string $countryCode
     * @param Request $request
     * @return FertilizerResourceCollection
     */
    public function byCountry(string $countryCode, Request $request)
    {
        $perPage = $request->input('per_page', 50); // Number of records per page, default is 50
        $orderBy = $request->input('order_by', 'sort_order'); // Default order by invoice_date
        $sort = $request->input('sort', 'asc'); // Default sort order is ascending

        $currencies = Fertilizer::query()
            ->where('country', strtoupper(trim($countryCode)))
            ->where('available', true)
            ->orderBy($orderBy, $sort)
            ->paginate($perPage);

        return FertilizerResourceCollection::make($currencies);
    }

}
