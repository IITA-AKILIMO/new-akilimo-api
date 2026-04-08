<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\FertilizerPriceRequest;
use App\Repositories\FertilizerPriceRepo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FertilizerPriceController extends AdminController
{
    public function __construct(protected FertilizerPriceRepo $repo) {}

    public function index(Request $request): Response
    {
        $paginator = $this->repo->paginateWithSort(
            perPage: (int) $request->get('per_page', 20),
            orderBy: 'sort_order',
            direction: 'asc',
        );

        return Inertia::render('FertilizerPrices/Index', [
            'items' => $this->paginateShape($paginator, fn ($f) => [
                'id' => $f->id,
                'country' => $f->country,
                'fertilizer_key' => $f->fertilizer_key,
                'min_price' => $f->min_price,
                'max_price' => $f->max_price,
                'price_per_bag' => $f->price_per_bag,
                'price_active' => (bool) $f->price_active,
                'sort_order' => $f->sort_order,
                'desc' => $f->desc,
            ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('FertilizerPrices/Create');
    }

    public function store(FertilizerPriceRequest $request): RedirectResponse
    {
        $this->repo->create($request->validated());

        return redirect()->route('admin.fertilizer-prices.index')
            ->with('success', 'Fertilizer price created.');
    }

    public function edit(int $id): Response
    {
        $item = $this->repo->findOrFail($id);

        return Inertia::render('FertilizerPrices/Edit', ['item' => $item->toArray()]);
    }

    public function update(FertilizerPriceRequest $request, int $id): RedirectResponse
    {
        $this->repo->update($id, $request->validated());

        return redirect()->route('admin.fertilizer-prices.index')
            ->with('success', 'Fertilizer price updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->repo->delete($id);

        return redirect()->route('admin.fertilizer-prices.index')
            ->with('success', 'Fertilizer price deleted.');
    }
}
