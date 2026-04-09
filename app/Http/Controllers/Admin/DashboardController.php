<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\ApiRequestStatsRepo;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(protected ApiRequestStatsRepo $statsRepo) {}

    public function index(Request $request): Response
    {
        $chartDays = (int) $request->input('chart_days', 30);
        $metricsDays = (int) $request->input('metrics_days', 7);

        $stats = $request->input('refresh')
            ? $this->statsRepo->refreshDashboardStats($chartDays, $metricsDays)
            : $this->statsRepo->getDashboardStats($chartDays, $metricsDays);

        return Inertia::render('Dashboard', $stats);
    }
}
