<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\OperationCostRequest;
use App\Repositories\OperationCostRepo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OperationCostController extends AdminController
{
    public function __construct(protected OperationCostRepo $repo) {}

    public function index(Request $request): Response
    {
        $paginator = $this->repo->paginateWithSort(
            perPage: (int) $request->get('per_page', 20),
            orderBy: 'created_at',
            direction: 'asc',
        );

        return Inertia::render('OperationCosts/Index', [
            'items' => $this->paginateShape($paginator, fn ($f) => [
                'id' => $f->id,
                'operation_name' => $f->operation_name,
                'operation_type' => $f->operation_type,
                'country_code' => $f->country_code,
                'min_cost' => $f->min_cost,
                'max_cost' => $f->max_cost,
                'is_active' => (bool) $f->is_active,
            ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('OperationCosts/Create');
    }

    public function store(OperationCostRequest $request): RedirectResponse
    {
        $this->repo->create($request->validated());

        return redirect()->route('admin.operation-costs.index')
            ->with('success', 'Operation cost created.');
    }

    public function edit(int $id): Response
    {
        $item = $this->repo->findOrFail($id);

        return Inertia::render('OperationCosts/Edit', ['item' => $item->toArray()]);
    }

    public function update(OperationCostRequest $request, int $id): RedirectResponse
    {
        $this->repo->update($id, $request->validated());

        return redirect()->route('admin.operation-costs.index')
            ->with('success', 'Operation cost updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->repo->delete($id);

        return redirect()->route('admin.operation-costs.index')
            ->with('success', 'Operation cost deleted.');
    }
}
