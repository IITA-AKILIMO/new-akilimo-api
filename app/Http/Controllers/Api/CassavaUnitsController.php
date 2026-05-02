<?php

namespace App\Http\Controllers\Api;

use App\Traits\HasPaginationParams;;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CassavaUnitRequest;
use App\Http\Resources\CassavaUnitResource;
use App\Http\Resources\Collections\CassavaUnitResourceCollection;
use App\Repositories\CassavaUnitRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CassavaUnitsController extends Controller
{
    use HasPaginationParams;

    public function __construct(protected CassavaUnitRepo $repo) {}

    public function index(Request $request): CassavaUnitResourceCollection
    {
        $perPage = $this->getPerPage($request);
        $orderBy = $this->getOrderBy($request, ['sort_order', 'created_at'], 'sort_order');
        $sort = $this->getSortDirection($request);

        $cassavaPrices = $this->repo->paginateWithSort(
            perPage: $perPage,
            orderBy: $orderBy,
            direction: $sort,
        );

        return CassavaUnitResourceCollection::make($cassavaPrices);
    }

    public function store(CassavaUnitRequest $request): JsonResponse
    {
        $unit = $this->repo->create($request->validated());

        return response()->json([
            'data' => new CassavaUnitResource($unit),
            'message' => 'Cassava unit created.',
        ], 201);
    }

    public function update(CassavaUnitRequest $request, int $id): JsonResponse
    {
        $unit = $this->repo->update($id, $request->validated());

        return response()->json([
            'data' => new CassavaUnitResource($unit),
            'message' => 'Cassava unit updated.',
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->repo->delete($id);

        return response()->json(null, 204);
    }
}
