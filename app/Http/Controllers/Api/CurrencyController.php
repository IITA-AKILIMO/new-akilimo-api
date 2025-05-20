<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\CurrencyResourceCollection;
use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 50); // Number of records per page, default is 50
        $orderBy = $request->input('order_by', 'currency_code'); // Default order by invoice_date
        $sort = $request->input('sort', 'asc'); // Default sort order is ascending

        $currencies = Currency::orderBy($orderBy, $sort)
            ->paginate($perPage);

        return CurrencyResourceCollection::make($currencies);
    }
}
