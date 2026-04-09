<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller;

abstract class AdminController extends Controller
{
    /**
     * Extract named filter values from the request, stripping blank strings.
     * Accepts a map of ['requestParam' => 'dbColumn'] or ['param'] for same-name params.
     */
    protected function filtersFrom(Request $request, array $keys): array
    {
        $filters = [];
        foreach ($keys as $param => $column) {
            if (is_int($param)) {
                $param = $column;
            }
            $value = $request->get($param);
            if ($value !== null && $value !== '') {
                $filters[$column] = $value;
            }
        }

        return $filters;
    }

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
