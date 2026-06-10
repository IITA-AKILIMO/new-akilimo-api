<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FertilizerPriceRequest;
use App\Http\Resources\Collections\FertilizerPriceResourceCollection;
use App\Http\Resources\FertilizerPriceResource;
use App\Repositories\FertilizerPriceRepo;
use App\Traits\HasPaginationParams;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\PathParameter;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FertilizerPriceController extends Controller
{
    use HasPaginationParams;

    public function __construct(
        protected FertilizerPriceRepo $repo
    ) {}

    /**
     * @unauthenticated
     */
    #[Endpoint(title: 'List Fertilizer Prices', description: 'Retrieves a paginated list of all fertilizer prices.')]
    #[QueryParameter(name: 'per_page', description: 'Number of items per page.', type: 'int')]
    #[QueryParameter(name: 'page', description: 'Page number.', type: 'int')]
    #[QueryParameter(name: 'sort', description: 'Field to sort by (sort_order, created_at).', type: 'string')]
    #[QueryParameter(name: 'order', description: 'Sort direction (asc or desc).', type: 'string')]
    public function index(Request $request): FertilizerPriceResourceCollection
    {
        return $this->getPaginatedPrices($request);
    }

    /**
     * @unauthenticated
     */
    #[Endpoint(title: 'Fertilizer Prices by Country', description: 'Retrieves a paginated list of fertilizer prices for a specific country.')]
    #[PathParameter(name: 'countryCode', description: 'ISO 3166-1 alpha-2 country code (e.g. NG, TZ).')]
    #[QueryParameter(name: 'per_page', description: 'Number of items per page.', type: 'int')]
    #[QueryParameter(name: 'page', description: 'Page number.', type: 'int')]
    #[QueryParameter(name: 'sort', description: 'Field to sort by (sort_order, created_at).', type: 'string')]
    #[QueryParameter(name: 'order', description: 'Sort direction (asc or desc).', type: 'string')]
    public function byCountry(string $countryCode, Request $request): FertilizerPriceResourceCollection
    {
        return $this->getPaginatedPrices($request, [
            'country' => strtoupper(trim($countryCode)),
        ]);
    }

    /**
     * @unauthenticated
     */
    #[Endpoint(title: 'Fertilizer Prices by Key', description: 'Retrieves a paginated list of fertilizer prices for a specific fertilizer key.')]
    #[PathParameter(name: 'fertilizerKey', description: 'The fertilizer key (e.g. UREA, MOP, DAP, NPK).')]
    #[QueryParameter(name: 'per_page', description: 'Number of items per page.', type: 'int')]
    #[QueryParameter(name: 'page', description: 'Page number.', type: 'int')]
    #[QueryParameter(name: 'sort', description: 'Field to sort by (sort_order, created_at).', type: 'string')]
    #[QueryParameter(name: 'order', description: 'Sort direction (asc or desc).', type: 'string')]
    public function byFertilizerKey(string $fertilizerKey, Request $request): FertilizerPriceResourceCollection
    {
        return $this->getPaginatedPrices($request, [
            'fertilizer_key' => strtoupper(trim($fertilizerKey)),
        ]);
    }

    public function store(FertilizerPriceRequest $request): JsonResponse
    {
        $price = $this->repo->create($request->validated());

        return response()->json([
            'data' => new FertilizerPriceResource($price),
            'message' => 'Fertilizer price created.',
        ], 201);
    }

    public function update(FertilizerPriceRequest $request, int $id): JsonResponse
    {
        $price = $this->repo->update($id, $request->validated());

        return response()->json([
            'data' => new FertilizerPriceResource($price),
            'message' => 'Fertilizer price updated.',
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->repo->delete($id);

        return response()->json(null, 204);
    }

    private function getPaginatedPrices(Request $request, array $filters = []): FertilizerPriceResourceCollection
    {
        $perPage = $this->getPerPage($request);
        $orderBy = $this->getOrderBy($request, ['sort_order', 'created_at'], 'sort_order');
        $sort = $this->getSortDirection($request);

        $fertilizerPrices = $this->repo->paginateWithSort(
            perPage: $perPage,
            orderBy: $orderBy,
            direction: $sort,
            filters: $filters
        );

        return FertilizerPriceResourceCollection::make($fertilizerPrices, $this->repo);
    }
}
