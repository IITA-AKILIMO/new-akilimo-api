<?php

namespace App\Http\Controllers\Api;

use App\Http\Concerns\HasPaginationParams;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FertilizerRequest;
use App\Http\Resources\Collections\FertilizerResourceCollection;
use App\Http\Resources\FertilizerResource;
use App\Repositories\FertilizerRepo;
use Illuminate\Http\JsonResponse;
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

    public function store(FertilizerRequest $request): JsonResponse
    {
        $fertilizer = $this->fertilizerRepo->create($request->validated());

        return response()->json([
            'data' => new FertilizerResource($fertilizer),
            'message' => 'Fertilizer created.',
        ], 201);
    }

    public function update(FertilizerRequest $request, int $id): JsonResponse
    {
        $fertilizer = $this->fertilizerRepo->update($id, $request->validated());

        return response()->json([
            'data' => new FertilizerResource($fertilizer),
            'message' => 'Fertilizer updated.',
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->fertilizerRepo->delete($id);

        return response()->json(null, 204);
    }
}
