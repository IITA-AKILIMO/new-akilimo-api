<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StarchPriceRequest;
use App\Repositories\StarchFactoryRepo;
use App\Repositories\StarchPriceRepo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StarchPriceController extends AdminController
{
    public function __construct(
        protected StarchPriceRepo $repo,
        protected StarchFactoryRepo $factoryRepo,
    ) {}

    public function index(Request $request): Response
    {
        $paginator = $this->repo->paginateWithSort(
            perPage: (int) $request->get('per_page', 20),
            orderBy: 'price_class',
            direction: 'asc',
        );

        return Inertia::render('StarchPrices/Index', [
            'items' => $this->paginateShape($paginator, fn ($f) => [
                'id' => $f->id,
                'starch_factory_id' => $f->starch_factory_id,
                'price_class' => $f->price_class,
                'min_starch' => $f->min_starch,
                'range_starch' => $f->range_starch,
                'price' => $f->price,
                'currency' => $f->currency,
            ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('StarchPrices/Create', [
            'factories' => $this->factoryRepo->all()->map(fn ($f) => [
                'id' => $f->id,
                'factory_name' => $f->factory_name,
                'country' => $f->country,
            ])->all(),
        ]);
    }

    public function store(StarchPriceRequest $request): RedirectResponse
    {
        $this->repo->create($request->validated());

        return redirect()->route('admin.starch-prices.index')
            ->with('success', 'Starch price created.');
    }

    public function edit(int $id): Response
    {
        $item = $this->repo->findOrFail($id);

        return Inertia::render('StarchPrices/Edit', [
            'item' => $item->toArray(),
            'factories' => $this->factoryRepo->all()->map(fn ($f) => [
                'id' => $f->id,
                'factory_name' => $f->factory_name,
                'country' => $f->country,
            ])->all(),
        ]);
    }

    public function update(StarchPriceRequest $request, int $id): RedirectResponse
    {
        $this->repo->update($id, $request->validated());

        return redirect()->route('admin.starch-prices.index')
            ->with('success', 'Starch price updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->repo->delete($id);

        return redirect()->route('admin.starch-prices.index')
            ->with('success', 'Starch price deleted.');
    }
}
