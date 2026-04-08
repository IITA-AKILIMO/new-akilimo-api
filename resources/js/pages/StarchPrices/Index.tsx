import { Link, router } from '@inertiajs/react'
import { useState } from 'react'
import ConfirmDialog from '../../components/ConfirmDialog'
import DataTable, { type Column } from '../../components/DataTable'
import AdminLayout from '../../layouts/AdminLayout'
import type { Paginated, StarchPrice } from '../../types'

interface Props { items: Paginated<StarchPrice> }

export default function StarchPricesIndex({ items }: Props) {
    const [deleting, setDeleting] = useState<StarchPrice | null>(null)
    const [processing, setProcessing] = useState(false)
    const columns: Column[] = [
        { key: 'starch_factory_id', label: 'Factory ID' },
        { key: 'price_class', label: 'Price Class' },
        { key: 'min_starch', label: 'Min Starch %' },
        { key: 'range_starch', label: 'Range' },
        { key: 'price', label: 'Price' },
        { key: 'currency', label: 'Currency' },
    ]
    function handlePageChange(page: number) { router.get('/admin/starch-prices', { page }, { preserveState: true }) }
    function handleDelete() {
        if (!deleting) return; setProcessing(true)
        router.delete(`/admin/starch-prices/${deleting.id}`, { onFinish: () => { setProcessing(false); setDeleting(null) } })
    }
    return (
        <AdminLayout title="Starch Prices">
            <div className="d-flex align-items-center justify-content-between mb-3">
                <span className="text-muted small">{items.meta.total} records</span>
                <Link href="/admin/starch-prices/create" className="btn btn-success btn-sm">+ New</Link>
            </div>
            <DataTable columns={columns} data={items.data as Record<string, unknown>[]} pagination={items.meta} links={items.links} sortBy="" onSort={() => {}} onPageChange={handlePageChange}
                actions={(row) => (<div className="d-flex gap-1 justify-content-end"><Link href={`/admin/starch-prices/${row.id}/edit`} className="btn btn-outline-secondary btn-sm">Edit</Link><button onClick={() => setDeleting(row as unknown as StarchPrice)} className="btn btn-outline-danger btn-sm">Delete</button></div>)} />
            <ConfirmDialog open={!!deleting} title="Delete starch price" message="Delete this starch price? This cannot be undone." onConfirm={handleDelete} onCancel={() => setDeleting(null)} processing={processing} />
        </AdminLayout>
    )
}
