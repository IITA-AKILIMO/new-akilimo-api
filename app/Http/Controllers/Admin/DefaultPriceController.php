<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\DefaultPriceRequest;
use App\Repositories\DefaultPriceRepo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DefaultPriceController extends AdminController
{
    public function __construct(protected DefaultPriceRepo $repo) {}

    public function index(Request $request): Response
    {
        $paginator = $this->repo->paginateWithSort(
            perPage: (int) $request->get('per_page', 20),
            orderBy: 'country',
            direction: 'asc',
        );

        return Inertia::render('DefaultPrices/Index', [
            'items' => $this->paginateShape($paginator, fn ($f) => [
                'id' => $f->id,
                'country' => $f->country,
                'item' => $f->item,
                'price' => $f->price,
                'unit' => $f->unit,
                'currency' => $f->currency,
            ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('DefaultPrices/Create');
    }

    public function store(DefaultPriceRequest $request): RedirectResponse
    {
        $this->repo->create($request->validated());

        return redirect()->route('admin.default-prices.index')
            ->with('success', 'Default price created.');
    }

    public function edit(int $id): Response
    {
        $item = $this->repo->findOrFail($id);

        return Inertia::render('DefaultPrices/Edit', ['item' => $item->toArray()]);
    }

    public function update(DefaultPriceRequest $request, int $id): RedirectResponse
    {
        $this->repo->update($id, $request->validated());

        return redirect()->route('admin.default-prices.index')
            ->with('success', 'Default price updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->repo->delete($id);

        return redirect()->route('admin.default-prices.index')
            ->with('success', 'Default price deleted.');
    }
}
