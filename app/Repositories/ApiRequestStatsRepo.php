<?php

namespace App\Repositories;

use App\Models\ApiKey;
use App\Models\Base\ApiRequest;
use App\Models\Base\Country;
use App\Models\Base\Fertilizer;
use App\Models\Base\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * @extends BaseRepo<ApiRequest>
 */
class ApiRequestStatsRepo extends BaseRepo
{
    protected function model(): string
    {
        return ApiRequest::class;
    }

    /**
     * Get dashboard stats with customizable day intervals.
     *
     * @param  int  $chartDays  Days for chart data (default 30)
     * @param  int  $metricsDays  Days for metrics calculations (default 7)
     * @param  int  $cacheMinutes  Cache duration in minutes (default 15)
     */
    public function getDashboardStats(
        int $chartDays = 30,
        int $metricsDays = 7,
        int $cacheMinutes = 15
    ): array {
        $cacheKey = "dashboard_stats_{$chartDays}_{$metricsDays}";

        return Cache::remember($cacheKey, $cacheMinutes * 60, function () use ($chartDays, $metricsDays) {
            return [
                'chartDays' => $chartDays,
                'stats' => $this->getSummaryStats(),
                'charts' => $this->getChartData($chartDays),
                'metrics' => $this->getMetrics($metricsDays),
                'recentRequests' => $this->getRecentRequests(),
            ];
        });
    }

    /**
     * Force refresh stats (clears cache).
     */
    public function refreshDashboardStats(int $chartDays = 30, int $metricsDays = 7): array
    {
        $cacheKey = "dashboard_stats_{$chartDays}_{$metricsDays}";
        Cache::forget($cacheKey);

        return $this->getDashboardStats($chartDays, $metricsDays);
    }

    protected function getSummaryStats(): array
    {
        return [
            // Count directly on the source table — avoids the view and its expressions
            'totalRequests' => (int) DB::table('api_requests')->count(),
            'totalUsers' => (int) User::count(),
            'activeKeys' => (int) ApiKey::where('is_active', true)
                ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
                ->count(),
            'totalFertilizers' => (int) Fertilizer::where('available', true)->count(),
            'totalCountries' => (int) Country::where('active', true)->count(),
        ];
    }

    protected function getChartData(int $days = 30): array
    {
        $since = now()->subDays($days);

        $dailyRequests = $this->model
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', $since)
            ->groupByRaw('DATE(created_at)')
            ->orderBy('date')
            ->get()
            ->map(fn ($row) => [
                'date' => $row->date,
                'count' => (int) $row->count,
            ]);

        $byUseCase = $this->model
            ->selectRaw('use_case, COUNT(*) as count')
            ->where('created_at', '>=', $since)
            ->where('use_case', '!=', 'NA')
            ->groupBy('use_case')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->map(fn ($row) => [
                'use_case' => $row->use_case,
                'count' => (int) $row->count,
            ]);

        return [
            'dailyRequests' => $dailyRequests,
            'byUseCase' => $byUseCase,
        ];
    }

    protected function getMetrics(int $days = 7): array
    {
        // Single query with conditional aggregation — hits the composite
        // (created_at, excluded) index added by the optimisation migration.
        $row = DB::table('api_requests')
            ->selectRaw('COUNT(*) as total, SUM(CASE WHEN excluded = 1 THEN 1 ELSE 0 END) as excluded_count')
            ->where('created_at', '>=', now()->subDays($days))
            ->first();

        $total = (int) ($row->total ?? 0);
        $excluded = (int) ($row->excluded_count ?? 0);
        $successRate = $total > 0
            ? round((($total - $excluded) / $total) * 100, 1)
            : 0;

        return [
            'avgResponseTimeMs' => 0, // Not available in source table
            'successRate' => $successRate,
            'requestsLastDays' => $total,
            'periodDays' => $days,
        ];
    }

    protected function getRecentRequests(): array
    {
        return $this->model
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->map(fn ($r) => [
                'id' => $r->id,
                'request_id' => $r->request_id,
                'device_token' => $r->device_token ? substr($r->device_token, 0, 8).'****' : null,
                'duration_ms' => $r->request_duration_ms,
                'created_at' => $r->created_at?->toIso8601String(),
                'use_case' => $r->use_case,
                'country_code' => $r->country_code,
            ])
            ->all();
    }
}
