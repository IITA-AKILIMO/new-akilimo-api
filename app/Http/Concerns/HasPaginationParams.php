<?php

namespace App\Http\Concerns;

use Illuminate\Http\Request;

trait HasPaginationParams
{
    /**
     * Returns a safe per_page value clamped between 1 and 100.
     */
    protected function getPerPage(Request $request, int $default = 50): int
    {
        return min(max(1, (int) $request->input('per_page', $default)), 100);
    }

    /**
     * Returns 'asc' or 'desc'; falls back to 'asc' for any other value.
     */
    protected function getSortDirection(Request $request): string
    {
        $sort = strtolower((string) $request->input('sort', 'asc'));
        return in_array($sort, ['asc', 'desc'], true) ? $sort : 'asc';
    }

    /**
     * Returns the requested order_by column only if it appears in $allowed;
     * falls back to $default otherwise to prevent arbitrary column injection.
     */
    protected function getOrderBy(Request $request, array $allowed, string $default): string
    {
        $orderBy = (string) $request->input('order_by', $default);
        return in_array($orderBy, $allowed, true) ? $orderBy : $default;
    }
}
