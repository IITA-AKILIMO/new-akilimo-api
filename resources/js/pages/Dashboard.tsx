import AdminLayout from '../layouts/AdminLayout'

const statCards = [
    { label: 'Total Requests', value: '—', description: 'All time compute requests' },
    { label: 'Active Users', value: '—', description: 'Users with active tokens' },
    { label: 'Fertilizers', value: '—', description: 'Available fertilizer records' },
    { label: 'Countries', value: '—', description: 'Supported country codes' },
]

export default function Dashboard() {
    return (
        <AdminLayout title="Dashboard">
            <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                {statCards.map((card) => (
                    <div
                        key={card.label}
                        className="rounded-xl border border-gray-200 bg-white p-6 shadow-sm"
                    >
                        <p className="text-sm font-medium text-gray-500">{card.label}</p>
                        <p className="mt-1 text-3xl font-bold text-gray-900">{card.value}</p>
                        <p className="mt-1 text-xs text-gray-400">{card.description}</p>
                    </div>
                ))}
            </div>

            <div className="mt-8 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 className="text-sm font-semibold text-gray-700">Recent Activity</h2>
                <p className="mt-3 text-sm text-gray-400">
                    Live stats will be wired up in Phase 6 using the{' '}
                    <code className="rounded bg-gray-100 px-1 text-xs">v_app_request_stats_view</code>.
                </p>
            </div>
        </AdminLayout>
    )
}
