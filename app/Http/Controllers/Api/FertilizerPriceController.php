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
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 50); // Number of records per page, default is 50
        $orderBy = $request->input('order_by', 'sort_order'); // Default order by invoice_date
        $sort = $request->input('sort', 'asc'); // Default sort order is ascending

        $prices = FertilizerPrice::orderBy($orderBy, $sort)
            ->paginate($perPage);

        return FertilizerPriceResourceCollection::make($prices);
    }

    public function priceByKey(string $fertilizerKey, Request $request)
    {
        $perPage = $request->input('per_page', 50); // Number of records per page, default is 50
        $orderBy = $request->input('order_by', 'sort_order'); // Default order by invoice_date
        $sort = $request->input('sort', 'asc'); // Default sort order is ascending

        $prices = FertilizerPrice::query()
            ->when($fertilizerKey != null, function ($query) use ($fertilizerKey) {
                return $query->where('fertilizer_key', strtoupper($fertilizerKey));
            })
            ->orderBy($orderBy, $sort)
            ->paginate($perPage);

        return FertilizerPriceResourceCollection::make($prices);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(FertilizerPrice $fertilizer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FertilizerPrice $fertilizer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FertilizerPrice $fertilizer)
    {
        //
    }
}
