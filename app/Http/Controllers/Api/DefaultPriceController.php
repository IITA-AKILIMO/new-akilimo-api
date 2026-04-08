<?php

namespace App\Http\Controllers\Api;

use App\Http\Concerns\HasPaginationParams;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DefaultPriceRequest;
use App\Http\Resources\Collections\DefaultPriceResourceCollection;
use App\Http\Resources\DefaultPriceResource;
use App\Models\DefaultPrice;
use App\Repositories\DefaultPriceRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DefaultPriceController extends Controller
{
    use HasPaginationParams;

    public function __construct(protected DefaultPriceRepo $repo) {}

    public function index(Request $request)
    {
        $perPage = $this->getPerPage($request);
        $orderBy = $this->getOrderBy($request, ['created_at'], 'created_at');
        $sort = $this->getSortDirection($request);

        $filters = [
            'country' => $request->input('country'),
        ];

        $starchPrices = $this->repo->paginateWithSort(
            perPage: $perPage,
            orderBy: $orderBy,
            direction: $sort,
            filters: $filters);

        return DefaultPriceResourceCollection::make($starchPrices);
    }

    public function store(DefaultPriceRequest $request): JsonResponse
    {
        // forceCreate is required because country + item are the composite PK
        // and are not in the base model's $fillable.
        $price = DefaultPrice::forceCreate($request->validated());

        return response()->json([
            'data' => new DefaultPriceResource($price),
            'message' => 'Default price created.',
        ], 201);
    }

    public function update(DefaultPriceRequest $request, string $country, string $item): JsonResponse
    {
        $price = DefaultPrice::where('country', strtoupper($country))
            ->where('item', $item)
            ->firstOrFail();

        $price->update($request->validated());

        return response()->json([
            'data' => new DefaultPriceResource($price->fresh()),
            'message' => 'Default price updated.',
        ]);
    }

    public function destroy(string $country, string $item): JsonResponse
    {
        DefaultPrice::where('country', strtoupper($country))
            ->where('item', $item)
            ->firstOrFail()
            ->delete();

        return response()->json(null, 204);
    }
}
