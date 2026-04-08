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

    /**
     * Extracts only filterable query parameters from the request.
     *
     * Example return:
     * [
     *   'country' => 'KE',
     *   'factory_id' => 12,
     *   'class' => 2
     * ]
     */
    protected function extractDbFilters(Request $request, array $allowed = []): array
    {
        // If you pass an $allowed list, only those keys will be returned
        $filters = $allowed ? $request->only($allowed) : $request->all();

        // Optionally strip out pagination/sorting keys
        unset($filters['per_page'], $filters['order_by'], $filters['sort']);

        return $filters;
    }
}
