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
            ->when($country != null, function ($query) use ($country) {
                return $query->where('country', strtoupper($country));
            })
            ->orderBy($orderBy, $sort)
            ->paginate($perPage);

        return FertilizerResourceCollection::make($currencies);
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
    public function show(Fertilizer $fertilizer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fertilizer $fertilizer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fertilizer $fertilizer)
    {
        //
    }
}
