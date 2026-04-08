<?php

namespace App\Http\Controllers\Api;

use App\Http\Concerns\HasPaginationParams;
use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\TranslationResourceCollection;
use App\Repositories\TranslationRepo;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    use HasPaginationParams;

    public function __construct(protected TranslationRepo $translationRepo) {}

    public function index(Request $request): TranslationResourceCollection
    {
        $perPage = $this->getPerPage($request);
        $orderBy = $this->getOrderBy($request, ['created_at'], 'created_at');
        $sort = $this->getSortDirection($request);

        $items = $this->translationRepo->paginateWithSort(
            perPage: $perPage,
            orderBy: $orderBy,
            direction: $sort,
        );

        return TranslationResourceCollection::make($items);
    }
}
