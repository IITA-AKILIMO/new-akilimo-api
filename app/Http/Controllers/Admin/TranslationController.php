<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\TranslationRequest;
use App\Repositories\TranslationRepo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TranslationController extends AdminController
{
    public function __construct(protected TranslationRepo $repo) {}

    public function index(Request $request): Response
    {
        $paginator = $this->repo->paginateWithSort(
            perPage: (int) $request->get('per_page', 20),
            orderBy: 'key',
            direction: 'asc',
        );

        return Inertia::render('Translations/Index', [
            'items' => $this->paginateShape($paginator, fn ($f) => [
                'id' => $f->id,
                'key' => $f->key,
                'en' => $f->en,
                'sw' => $f->sw,
                'rw' => $f->rw,
            ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Translations/Create');
    }

    public function store(TranslationRequest $request): RedirectResponse
    {
        $this->repo->create($request->validated());

        return redirect()->route('admin.translations.index')
            ->with('success', 'Translation created.');
    }

    public function edit(int $id): Response
    {
        $item = $this->repo->findOrFail($id);

        return Inertia::render('Translations/Edit', ['item' => $item->toArray()]);
    }

    public function update(TranslationRequest $request, int $id): RedirectResponse
    {
        $this->repo->update($id, $request->validated());

        return redirect()->route('admin.translations.index')
            ->with('success', 'Translation updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->repo->delete($id);

        return redirect()->route('admin.translations.index')
            ->with('success', 'Translation deleted.');
    }
}
