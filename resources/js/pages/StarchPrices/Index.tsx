import { Link, router } from '@inertiajs/react'
import { useState } from 'react'
import ConfirmDialog from '../../components/ConfirmDialog'
import DataTable, { type Column } from '../../components/DataTable'
import AdminLayout from '../../layouts/AdminLayout'
import type { Paginated, StarchFactory, StarchPrice } from '../../types'

interface Filters { starch_factory_id: string }
interface Props { items: Paginated<StarchPrice>; filters: Filters; factories: Pick<StarchFactory, 'id' | 'factory_name' | 'country'>[] }

export default function StarchPricesIndex({ items, filters, factories }: Props) {
    const [deleting, setDeleting] = useState<StarchPrice | null>(null)
    const [processing, setProcessing] = useState(false)
    const columns: Column[] = [
        { key: 'starch_factory_id', label: 'Factory', render: (v) => factories.find((f) => f.id === v)?.factory_name ?? String(v) },
        { key: 'price_class', label: 'Price Class' },
        { key: 'min_starch', label: 'Min Starch %' },
        { key: 'range_starch', label: 'Range' },
        { key: 'price', label: 'Price' },
        { key: 'currency', label: 'Currency' },
    ]
    function navigate(next: Partial<Filters>) {
        router.get('/admin/starch-prices', { ...filters, ...next, page: 1 }, { preserveState: true })
    }
    function handlePageChange(page: number) { router.get('/admin/starch-prices', { ...filters, page }, { preserveState: true }) }
    function handleDelete() {
        if (!deleting) return; setProcessing(true)
        router.delete(`/admin/starch-prices/${deleting.id}`, { onFinish: () => { setProcessing(false); setDeleting(null) } })
    }
    return (
        <AdminLayout title="Starch Prices">
            <div className="d-flex align-items-center justify-content-between mb-3">
                <span className="text-muted small">{items.meta.total} records</span>
                <div className="d-flex gap-2">
                    <Link href="/admin/starch-prices/batch-edit" className="btn btn-outline-secondary btn-sm">Batch Edit</Link>
                    <Link href="/admin/starch-prices/batch-create" className="btn btn-outline-success btn-sm">Batch Add</Link>
                    <Link href="/admin/starch-prices/create" className="btn btn-success btn-sm">+ New</Link>
                </div>
            </div>
            <div className="row g-2 mb-3">
                <div className="col-auto">
                    <select className="form-select form-select-sm" value={filters.starch_factory_id} onChange={(e) => navigate({ starch_factory_id: e.target.value })} style={{ minWidth: 200 }}>
                        <option value="">All Factories</option>
                        {factories.map((f) => (
                            <option key={f.id} value={String(f.id)}>{f.factory_name} ({f.country})</option>
                        ))}
                    </select>
                </div>
                {filters.starch_factory_id && (
                    <div className="col-auto">
                        <button className="btn btn-outline-secondary btn-sm" onClick={() => navigate({ starch_factory_id: '' })}>Clear</button>
                    </div>
                )}
            </div>
            <DataTable columns={columns} data={items.data as Record<string, unknown>[]} pagination={items.meta} links={items.links} sortBy="" onSort={() => {}} onPageChange={handlePageChange}
                actions={(row) => (<div className="d-flex gap-1 justify-content-end"><Link href={`/admin/starch-prices/${row.id}/edit`} className="btn btn-outline-secondary btn-sm">Edit</Link><button onClick={() => setDeleting(row as unknown as StarchPrice)} className="btn btn-outline-danger btn-sm">Delete</button></div>)} />
            <ConfirmDialog open={!!deleting} title="Delete starch price" message="Delete this starch price? This cannot be undone." onConfirm={handleDelete} onCancel={() => setDeleting(null)} processing={processing} />
        </AdminLayout>
    )
}
