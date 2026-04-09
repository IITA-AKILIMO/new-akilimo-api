import { Link, router } from '@inertiajs/react'
import { useState } from 'react'
import Badge from '../../components/Badge'
import ConfirmDialog from '../../components/ConfirmDialog'
import CountryFilter from '../../components/CountryFilter'
import DataTable, { type Column } from '../../components/DataTable'
import AdminLayout from '../../layouts/AdminLayout'
import type { Paginated, PotatoPrice } from '../../types'

interface Filters { country: string }
interface Props { items: Paginated<PotatoPrice>; filters: Filters }

export default function PotatoPricesIndex({ items, filters }: Props) {
    const [deleting, setDeleting] = useState<PotatoPrice | null>(null)
    const [processing, setProcessing] = useState(false)
    const columns: Column[] = [
        { key: 'country', label: 'Country' },
        { key: 'min_price', label: 'Min Price' },
        { key: 'max_price', label: 'Max Price' },
        { key: 'min_usd', label: 'Min USD' },
        { key: 'max_usd', label: 'Max USD' },
        { key: 'price_active', label: 'Active', render: (v) => <Badge active={!!v} /> },
    ]
    function navigate(next: Partial<Filters>) {
        router.get('/admin/potato-prices', { ...filters, ...next, page: 1 }, { preserveState: true })
    }
    function handlePageChange(page: number) { router.get('/admin/potato-prices', { ...filters, page }, { preserveState: true }) }
    function handleDelete() {
        if (!deleting) return; setProcessing(true)
        router.delete(`/admin/potato-prices/${deleting.id}`, { onFinish: () => { setProcessing(false); setDeleting(null) } })
    }
    return (
        <AdminLayout title="Potato Prices">
            <div className="d-flex align-items-center justify-content-between mb-3">
                <span className="text-muted small">{items.meta.total} records</span>
                <Link href="/admin/potato-prices/create" className="btn btn-success btn-sm">+ New</Link>
            </div>
            <div className="row g-2 mb-3">
                <div className="col-auto">
                    <CountryFilter value={filters.country} onChange={(v) => navigate({ country: v })} />
                </div>
                {filters.country && (
                    <div className="col-auto">
                        <button className="btn btn-outline-secondary btn-sm" onClick={() => navigate({ country: '' })}>Clear</button>
                    </div>
                )}
            </div>
            <DataTable columns={columns} data={items.data as Record<string, unknown>[]} pagination={items.meta} links={items.links} sortBy="" onSort={() => {}} onPageChange={handlePageChange}
                actions={(row) => (<div className="d-flex gap-1 justify-content-end"><Link href={`/admin/potato-prices/${row.id}/edit`} className="btn btn-outline-secondary btn-sm">Edit</Link><button onClick={() => setDeleting(row as unknown as PotatoPrice)} className="btn btn-outline-danger btn-sm">Delete</button></div>)} />
            <ConfirmDialog open={!!deleting} title="Delete potato price" message="Delete this potato price? This cannot be undone." onConfirm={handleDelete} onCancel={() => setDeleting(null)} processing={processing} />
        </AdminLayout>
    )
}
