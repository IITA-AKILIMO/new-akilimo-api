<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\FertilizerPriceResourceCollection;
use App\Models\FertilizerPrice;
use Illuminate\Http\Request;

class FertilizerPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): FertilizerPriceResourceCollection
    {
        $perPage = $request->input('per_page', 50); // Number of records per page, default is 50
        $orderBy = $request->input('order_by', 'sort_order'); // Default order by invoice_date
        $sort = $request->input('sort', 'asc'); // Default sort order is ascending

        $prices = FertilizerPrice::orderBy($orderBy, $sort)
            ->paginate($perPage);

        return FertilizerPriceResourceCollection::make($prices);
    }

    public function byFertiilizerKey(string $fertilizerKey, Request $request): FertilizerPriceResourceCollection
    {
        $perPage = $request->input('per_page', 50); // Number of records per page, default is 50
        $orderBy = $request->input('order_by', 'sort_order'); // Default order by invoice_date
        $sort = $request->input('sort', 'asc'); // Default sort order is ascending

        $prices = FertilizerPrice::query()
            ->where('fertilizer_key', strtoupper(trim($fertilizerKey)))
            ->where('price_active', true)
            ->orderBy($orderBy, $sort)
            ->paginate($perPage);

        return FertilizerPriceResourceCollection::make($prices);
    }

}
