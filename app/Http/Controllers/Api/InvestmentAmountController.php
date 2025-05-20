<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\InvestmentAmountResourceCollection;
use App\Models\InvestmentAmount;
use Illuminate\Http\Request;

class InvestmentAmountController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function byCountry(string $countryCode, Request $request)
    {
        $perPage = $request->input('per_page', 50); // Number of records per page, default is 50
        $orderBy = $request->input('order_by', 'sort_order'); // Default order by invoice_date
        $sort = $request->input('sort', 'asc'); // Default sort order is ascending

        $investmentAmount = InvestmentAmount::query()
            ->where('country', strtoupper(trim($countryCode)))
            ->where('price_active', true)
            ->orderBy($orderBy, $sort)
            ->paginate($perPage);

        return InvestmentAmountResourceCollection::make($investmentAmount);
    }

}
