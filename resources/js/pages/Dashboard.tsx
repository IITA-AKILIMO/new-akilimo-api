import AdminLayout from '../layouts/AdminLayout'

const statCards = [
    { label: 'Total Requests', value: '—', description: 'All time compute requests', color: 'success' },
    { label: 'Active Users', value: '—', description: 'Users with active tokens', color: 'primary' },
    { label: 'Fertilizers', value: '—', description: 'Available fertilizer records', color: 'info' },
    { label: 'Countries', value: '—', description: 'Supported country codes', color: 'warning' },
]

export default function Dashboard() {
    return (
        <AdminLayout title="Dashboard">
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

            <div className="card shadow-sm">
                <div className="card-header bg-white py-3">
                    <h6 className="mb-0 fw-semibold">Recent Activity</h6>
                </div>
                <div className="card-body text-center text-muted py-5">
                    <svg className="mb-2" width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <div className="small">
                        Live stats will be wired up in Phase 6 using{' '}
                        <code>v_app_request_stats_view</code>.
                    </div>
                </div>
            </div>
        </AdminLayout>
    )
}
