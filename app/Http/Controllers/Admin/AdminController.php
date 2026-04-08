<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller;

abstract class AdminController extends Controller
{
    /**
     * Convert a LengthAwarePaginator to the Paginated<T> shape expected by the frontend.
     */
    protected function paginateShape(LengthAwarePaginator $paginator, callable $map): array
    {
        return [
            'data' => collect($paginator->items())->map($map)->all(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
            'links' => [
                'first' => $paginator->url(1),
                'last' => $paginator->url($paginator->lastPage()),
                'prev' => $paginator->previousPageUrl(),
                'next' => $paginator->nextPageUrl(),
            ],
        ];
    }
}
