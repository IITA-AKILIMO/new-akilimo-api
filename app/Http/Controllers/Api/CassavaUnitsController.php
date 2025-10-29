<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\CassavaUnitResourceCollection;
use App\Models\CassavaUnit;
use Illuminate\Http\Request;

class CassavaUnitsController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 50); // Number of records per page, default is 50
        $orderBy = $request->input('order_by', 'sort_order'); // Default order by invoice_date
        $sort = $request->input('sort', 'asc'); // Default sort order is ascending

        $cassavaPrices = CassavaUnit::query()
            ->where('is_active', true)
            ->orderBy($orderBy, $sort)
            ->paginate($perPage);

        return CassavaUnitResourceCollection::make($cassavaPrices);
    }
}
