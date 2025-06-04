<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\FertilizerResourceCollection;
use App\Repositories\FertilizerRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FertilizerController extends Controller
{
    public function __construct(
        protected FertilizerRepo $fertilizerRepo,
    )
    {
        //empty constructor
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): FertilizerResourceCollection
    {
        $perPage = $request->input('per_page', 50); // Number of records per page, default is 50
        $orderBy = $request->input('order_by', 'sort_order'); // Default order by invoice_date
        $sort = $request->input('sort', 'asc'); // Default sort order is ascending

        $availableFertilizers = $this->fertilizerRepo->paginateWithSort(
            perPage: $perPage,
            sortBy: $orderBy,
            direction: $sort);

        return FertilizerResourceCollection::make($availableFertilizers);
    }

    /**
     * @param string $countryCode
     * @param Request $request
     * @return FertilizerResourceCollection
     */
    public function byCountry(string $countryCode, Request $request): FertilizerResourceCollection
    {
        $perPage = $request->input('per_page', 50); // Number of records per page, default is 50
        $orderBy = $request->input('order_by', 'sort_order'); // Default order by invoice_date
        $sort = $request->input('sort', 'asc'); // Default sort order is ascending
        $useCase = $request->input('use_case');


        $filters = [
            'country' => strtoupper($countryCode),
            'available' => true
        ];

        $trimmed = Str::of($useCase)->trim();
        if ($trimmed->isNotEmpty()) {
            $filters['use_case'] = $trimmed->upper()->toString();
        }


        $availableFertilizers = $this->fertilizerRepo->paginateWithSort(
            perPage: $perPage,
            sortBy: $orderBy,
            direction: $sort,
            filters: $filters);


        return FertilizerResourceCollection::make($availableFertilizers);
    }

}
