<?php

namespace App\Http\Controllers\Api;

use App\Http\Concerns\HasPaginationParams;
use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\FertilizerResourceCollection;
use App\Repositories\FertilizerRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FertilizerController extends Controller
{
    use HasPaginationParams;

    public function __construct(
        protected FertilizerRepo $fertilizerRepo,
    ) {
        // empty constructor
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): FertilizerResourceCollection
    {
        $perPage = $this->getPerPage($request);
        $orderBy = $this->getOrderBy($request, ['sort_order', 'name', 'created_at'], 'sort_order');
        $sort = $this->getSortDirection($request);

        $availableFertilizers = $this->fertilizerRepo->paginateWithSort(
            perPage: $perPage,
            orderBy: $orderBy,
            direction: $sort);

        return FertilizerResourceCollection::make($availableFertilizers);
    }

    public function byCountry(string $countryCode, Request $request): FertilizerResourceCollection
    {
        $perPage = $this->getPerPage($request);
        $orderBy = $this->getOrderBy($request, ['sort_order', 'name', 'created_at'], 'sort_order');
        $sort = $this->getSortDirection($request);
        $useCase = $request->input('use_case');

        $filters = [
            'country' => strtoupper($countryCode),
        ];

        $trimmed = Str::of($useCase)->trim();
        if ($trimmed->isNotEmpty()) {
            $filters['use_case'] = $trimmed->upper()->toString();
        }

        $availableFertilizers = $this->fertilizerRepo->paginateWithSort(
            perPage: $perPage,
            orderBy: $orderBy,
            direction: $sort,
            filters: $filters);

        return FertilizerResourceCollection::make($availableFertilizers);
    }
}
