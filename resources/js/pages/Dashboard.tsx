import { router, usePage } from '@inertiajs/react'
import { useCallback, useState } from 'react'
import AdminLayout from '../layouts/AdminLayout'

interface DailyRequest {
    date: string
    count: number
}

interface UseCaseStat {
    use_case: string
    count: number
}

interface DashboardProps {
    chartDays: number
    stats: {
        totalRequests: number
        totalUsers: number
        activeKeys: number
        totalFertilizers: number
        totalCountries: number
    }
    charts: {
        dailyRequests: DailyRequest[]
        byUseCase: UseCaseStat[]
    }
    metrics: {
        avgResponseTimeMs: number
        successRate: number
        requestsLastDays: number
        periodDays: number
    }
    recentRequests: {
        id: number
        request_id: string | number
        device_token: string | null
        duration_ms: number | null
        created_at: string
        use_case?: string | null
        country_code?: string | null
    }[]
}

const CHART_PERIODS = [7, 14, 30, 90]
const METRIC_PERIODS = [7, 14, 30]

function navigate(chartDays: number, metricsDays: number, refresh = false) {
    const params: Record<string, string | number> = { chart_days: chartDays, metrics_days: metricsDays }
    if (refresh) params.refresh = 1
    router.get('/admin', params, { preserveScroll: true, replace: true })
}

function Tooltip({ text, children }: { text: string; children: React.ReactNode }) {
    const [visible, setVisible] = useState(false)
    return (
        <span
            style={{ position: 'relative', display: 'inline-block' }}
            onMouseEnter={() => setVisible(true)}
            onMouseLeave={() => setVisible(false)}
        >
            {children}
            {visible && (
                <span
                    style={{
                        position: 'absolute',
                        bottom: '110%',
                        left: '50%',
                        transform: 'translateX(-50%)',
                        background: 'rgba(0,0,0,0.8)',
                        color: '#fff',
                        padding: '3px 8px',
                        borderRadius: 4,
                        fontSize: 12,
                        whiteSpace: 'nowrap',
                        pointerEvents: 'none',
                        zIndex: 10,
                    }}
                >
                    {text}
                </span>
            )}
        </span>
    )
}

export default function Dashboard() {
    const props = usePage().props as unknown as DashboardProps
    const data = props

    const chartDays = data.chartDays ?? 30
    const metricsDays = data.metrics?.periodDays ?? 7
    const [refreshing, setRefreshing] = useState(false)

    const handleRefresh = useCallback(() => {
        setRefreshing(true)
        router.get(
            '/admin',
            { chart_days: chartDays, metrics_days: metricsDays, refresh: 1 },
            {
                preserveScroll: true,
                replace: true,
                onFinish: () => setRefreshing(false),
            },
        )
    }, [chartDays, metricsDays])

    const statCards = data
        ? [
              { label: 'Total Requests', value: data.stats.totalRequests.toLocaleString(), description: 'All time compute requests', color: 'success' },
              { label: 'Active Users', value: data.stats.totalUsers.toLocaleString(), description: 'Registered users', color: 'primary' },
              { label: 'Active API Keys', value: data.stats.activeKeys.toLocaleString(), description: 'Currently valid keys', color: 'info' },
              { label: 'Countries', value: data.stats.totalCountries.toString(), description: 'Supported countries', color: 'warning' },
          ]
        : [
              { label: 'Total Requests', value: '—', description: 'All time compute requests', color: 'success' },
              { label: 'Active Users', value: '—', description: 'Registered users', color: 'primary' },
              { label: 'Active API Keys', value: '—', description: 'Currently valid keys', color: 'info' },
              { label: 'Countries', value: '—', description: 'Supported countries', color: 'warning' },
          ]

    const maxDaily = data?.charts.dailyRequests?.length ? Math.max(...data.charts.dailyRequests.map((d) => d.count)) : 0

    const totalUseCases = data?.charts.byUseCase?.reduce((sum, r) => sum + r.count, 0) ?? 0

    const successColor =
        data?.metrics.successRate >= 95 ? 'text-success' : data?.metrics.successRate >= 80 ? 'text-warning' : 'text-danger'

    return (
        <AdminLayout title="Dashboard">
            {/* Filter toolbar */}
            <div className="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                <div className="d-flex flex-wrap align-items-center gap-3">
                    {/* Chart period */}
                    <div className="d-flex align-items-center gap-2">
                        <span className="text-muted small fw-medium">Chart:</span>
                        <div className="btn-group btn-group-sm">
                            {CHART_PERIODS.map((d) => (
                                <button
                                    key={d}
                                    type="button"
                                    className={`btn ${chartDays === d ? 'btn-primary' : 'btn-outline-secondary'}`}
                                    onClick={() => navigate(d, metricsDays)}
                                >
                                    {d}d
                                </button>
                            ))}
                        </div>
                    </div>

                    {/* Metrics period */}
                    <div className="d-flex align-items-center gap-2">
                        <span className="text-muted small fw-medium">Metrics:</span>
                        <div className="btn-group btn-group-sm">
                            {METRIC_PERIODS.map((d) => (
                                <button
                                    key={d}
                                    type="button"
                                    className={`btn ${metricsDays === d ? 'btn-info' : 'btn-outline-secondary'}`}
                                    onClick={() => navigate(chartDays, d)}
                                >
                                    {d}d
                                </button>
                            ))}
                        </div>
                    </div>
                </div>

                {/* Refresh */}
                <button
                    type="button"
                    className="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1"
                    onClick={handleRefresh}
                    disabled={refreshing}
                >
                    <svg
                        width="14"
                        height="14"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        strokeWidth={2}
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        style={{ animation: refreshing ? 'spin 1s linear infinite' : undefined }}
                    >
                        <path d="M21 2v6h-6M3 12a9 9 0 0 1 15-6.7L21 8M3 22v-6h6M21 12a9 9 0 0 1-15 6.7L3 16" />
                    </svg>
                    {refreshing ? 'Refreshing…' : 'Refresh'}
                </button>
            </div>

            {/* Stats Cards */}
            <div className="row g-4 mb-4">
                {statCards.map((card) => (
                    <div key={card.label} className="col-sm-6 col-xl-3">
                        <div className="card shadow-sm h-100">
                            <div className="card-body">
                                <p className="text-muted small fw-medium text-uppercase mb-1">{card.label}</p>
                                <p className={`fs-2 fw-bold text-${card.color} mb-1`}>{card.value}</p>
                                <p className="text-muted small mb-0">{card.description}</p>
                            </div>
                        </div>
                    </div>
                ))}
            </div>

            {/* Metrics Row */}
            {data && (
                <div className="row g-4 mb-4">
                    <div className="col-md-4">
                        <div className="card shadow-sm h-100">
                            <div className="card-body">
                                <p className="text-muted small fw-medium text-uppercase mb-1">Avg Response Time</p>
                                <p className="fs-3 fw-bold text-dark mb-1">{data.metrics.avgResponseTimeMs}ms</p>
                                <p className="text-muted small mb-0">Last {metricsDays} days</p>
                            </div>
                        </div>
                    </div>
                    <div className="col-md-4">
                        <div className="card shadow-sm h-100">
                            <div className="card-body">
                                <p className="text-muted small fw-medium text-uppercase mb-1">Success Rate</p>
                                <p className={`fs-3 fw-bold mb-1 ${successColor}`}>{data.metrics.successRate}%</p>
                                <p className="text-muted small mb-0">Last {metricsDays} days</p>
                            </div>
                        </div>
                    </div>
                    <div className="col-md-4">
                        <div className="card shadow-sm h-100">
                            <div className="card-body">
                                <p className="text-muted small fw-medium text-uppercase mb-1">Requests</p>
                                <p className="fs-3 fw-bold text-primary mb-1">{data.metrics.requestsLastDays.toLocaleString()}</p>
                                <p className="text-muted small mb-0">Last {metricsDays} days</p>
                            </div>
                        </div>
                    </div>
                </div>
            )}

            <div className="row g-4 mb-4">
                {/* Daily Requests Bar Chart */}
                <div className="col-lg-8">
                    <div className="card shadow-sm h-100">
                        <div className="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                            <h6 className="mb-0 fw-semibold">Requests (Last {chartDays} Days)</h6>
                            {data?.charts.dailyRequests?.length > 0 && (
                                <span className="badge bg-light text-secondary border">
                                    peak {maxDaily.toLocaleString()}
                                </span>
                            )}
                        </div>
                        <div className="card-body">
                            {data?.charts.dailyRequests?.length ? (
                                <>
                                    <div className="d-flex align-items-end gap-1" style={{ height: 200 }}>
                                        {data.charts.dailyRequests.map((day, i) => (
                                            <Tooltip key={i} text={`${day.date}: ${day.count.toLocaleString()}`}>
                                                <div
                                                    className="flex-fill bg-primary rounded-top"
                                                    style={{
                                                        height: `${maxDaily ? (day.count / maxDaily) * 100 : 0}%`,
                                                        minHeight: day.count > 0 ? '4px' : '0',
                                                        cursor: 'default',
                                                        transition: 'opacity 0.15s',
                                                    }}
                                                    onMouseEnter={(e) => ((e.currentTarget as HTMLElement).style.opacity = '0.75')}
                                                    onMouseLeave={(e) => ((e.currentTarget as HTMLElement).style.opacity = '1')}
                                                />
                                            </Tooltip>
                                        ))}
                                    </div>
                                    <div className="d-flex justify-content-between mt-2">
                                        <span className="small text-muted">{data.charts.dailyRequests[0]?.date}</span>
                                        <span className="small text-muted">
                                            {data.charts.dailyRequests[data.charts.dailyRequests.length - 1]?.date}
                                        </span>
                                    </div>
                                </>
                            ) : (
                                <div className="text-center text-muted py-5">No request data available</div>
                            )}
                        </div>
                    </div>
                </div>

                {/* Requests by Use Case */}
                <div className="col-lg-4">
                    <div className="card shadow-sm h-100">
                        <div className="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                            <h6 className="mb-0 fw-semibold">By Use Case</h6>
                            {totalUseCases > 0 && (
                                <span className="badge bg-light text-secondary border">{totalUseCases.toLocaleString()} total</span>
                            )}
                        </div>
                        <div className="card-body">
                            {data?.charts.byUseCase?.length ? (
                                <div className="d-flex flex-column gap-3">
                                    {data.charts.byUseCase.map((item, i) => {
                                        const pct = totalUseCases > 0 ? Math.round((item.count / totalUseCases) * 100) : 0
                                        return (
                                            <div key={i}>
                                                <div className="d-flex justify-content-between mb-1">
                                                    <span className="small text-truncate" style={{ maxWidth: '70%' }}>
                                                        {item.use_case || 'unknown'}
                                                    </span>
                                                    <span className="small text-muted">
                                                        {item.count.toLocaleString()} ({pct}%)
                                                    </span>
                                                </div>
                                                <div className="progress" style={{ height: 6 }}>
                                                    <div
                                                        className="progress-bar"
                                                        style={{ width: `${pct}%` }}
                                                        role="progressbar"
                                                        aria-valuenow={pct}
                                                        aria-valuemin={0}
                                                        aria-valuemax={100}
                                                    />
                                                </div>
                                            </div>
                                        )
                                    })}
                                </div>
                            ) : (
                                <div className="text-center text-muted py-5">No use case data</div>
                            )}
                        </div>
                    </div>
                </div>
            </div>

            {/* Recent Requests Table */}
            <div className="card shadow-sm">
                <div className="card-header bg-white py-3">
                    <h6 className="mb-0 fw-semibold">Recent Requests</h6>
                </div>
                <div className="card-body p-0">
                    {data?.recentRequests?.length ? (
                        <div className="table-responsive">
                            <table className="table table-hover mb-0">
                                <thead className="table-light">
                                    <tr>
                                        <th className="px-3 py-2">ID</th>
                                        <th className="px-3 py-2">Device</th>
                                        <th className="px-3 py-2">Country</th>
                                        <th className="px-3 py-2">Use Case</th>
                                        <th className="px-3 py-2">Request Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {data.recentRequests.map((req) => (
                                        <tr key={req.id}>
                                            <td className="px-3 py-2 text-muted small">{req.id}</td>
                                            <td className="px-3 py-2">
                                                <code className="small">{req.device_token || '—'}</code>
                                            </td>
                                            <td className="px-3 py-2">
                                                {req.country_code ? (
                                                    <span className="badge bg-light text-dark border">{req.country_code}</span>
                                                ) : (
                                                    '—'
                                                )}
                                            </td>
                                            <td className="px-3 py-2 small">{req.use_case || '—'}</td>
                                            <td className="px-3 py-2 small text-muted">
                                                {req.created_at ? new Date(req.created_at).toLocaleString() : '—'}
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    ) : (
                        <div className="text-center text-muted py-4">No recent requests</div>
                    )}
                </div>
            </div>

            <style>{`
                @keyframes spin { to { transform: rotate(360deg); } }
            `}</style>
        </AdminLayout>
    )
}
