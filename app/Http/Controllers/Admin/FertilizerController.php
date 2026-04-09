<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\FertilizerRequest;
use App\Repositories\FertilizerRepo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FertilizerController extends AdminController
{
    public function __construct(protected FertilizerRepo $repo) {}

    public function index(Request $request): Response
    {
        $filters = $this->filtersFrom($request, ['country', 'use_case', 'available']);
        $paginator = $this->repo->paginateWithSort(
            perPage: (int) $request->get('per_page', 20),
            orderBy: 'sort_order',
            direction: 'asc',
            filters: $filters,
        );

        return Inertia::render('Fertilizers/Index', [
            'filters' => [
                'country' => $request->get('country', ''),
                'use_case' => $request->get('use_case', ''),
                'available' => $request->get('available', ''),
            ],
            'items' => $this->paginateShape($paginator, fn ($f) => [
                'id' => $f->id,
                'name' => $f->name,
                'type' => $f->type,
                'fertilizer_key' => $f->fertilizer_key,
                'fertilizer_label' => $f->fertilizer_label,
                'country' => $f->country,
                'weight' => $f->weight,
                'use_case' => $f->use_case,
                'available' => (bool) $f->available,
                'cis' => (bool) $f->cis,
                'cim' => (bool) $f->cim,
                'sort_order' => $f->sort_order,
            ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Fertilizers/Create');
    }

    public function store(FertilizerRequest $request): RedirectResponse
    {
        $this->repo->create($request->validated());

        return redirect()->route('admin.fertilizers.index')
            ->with('success', 'Fertilizer created.');
    }

    public function edit(int $id): Response
    {
        $item = $this->repo->findOrFail($id);

        return Inertia::render('Fertilizers/Edit', ['item' => $item->toArray()]);
    }

    public function update(FertilizerRequest $request, int $id): RedirectResponse
    {
        $this->repo->update($id, $request->validated());

        return redirect()->route('admin.fertilizers.index')
            ->with('success', 'Fertilizer updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->repo->delete($id);

        return redirect()->route('admin.fertilizers.index')
            ->with('success', 'Fertilizer deleted.');
    }
}
