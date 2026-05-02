import { Link, router } from '@inertiajs/react'
import { useState } from 'react'
import Badge from '../../components/Badge'
import ConfirmDialog from '../../components/ConfirmDialog'
import DataTable, { type Column } from '../../components/DataTable'
import AdminLayout from '../../layouts/AdminLayout'
import type { CassavaUnit, Paginated } from '../../types'

interface Filters { is_active: string }
interface Props { items: Paginated<CassavaUnit>; filters: Filters }

export default function CassavaUnitsIndex({ items, filters }: Props) {
    const [deleting, setDeleting] = useState<CassavaUnit | null>(null)
    const [processing, setProcessing] = useState(false)
    const columns: Column[] = [
        { key: 'label', label: 'Label' },
        { key: 'unit_weight', label: 'Weight (kg)' },
        { key: 'description', label: 'Description' },
        { key: 'is_active', label: 'Active', render: (v) => <Badge active={!!v} /> },
    ]
    function navigate(next: Partial<Filters>) {
        router.get('/admin/cassava-units', { ...filters, ...next, page: 1 }, { preserveState: true })
    }
    function handlePageChange(page: number) { router.get('/admin/cassava-units', { ...filters, page }, { preserveState: true }) }
    function handleDelete() {
        if (!deleting) return; setProcessing(true)
        router.delete(`/admin/cassava-units/${deleting.id}`, { onFinish: () => { setProcessing(false); setDeleting(null) } })
    }
    return (
        <AdminLayout title="Cassava Units">
            <div className="d-flex align-items-center justify-content-between mb-3">
                <span className="text-muted small">{items.meta.total} records</span>
                <Link href="/admin/cassava-units/create" className="btn btn-success btn-sm">+ New</Link>
            </div>
            <div className="row g-2 mb-3">
                <div className="col-auto">
                    <select className="form-select form-select-sm" value={filters.is_active} onChange={(e) => navigate({ is_active: e.target.value })}>
                        <option value="">All Statuses</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                {filters.is_active !== '' && (
                    <div className="col-auto">
                        <button className="btn btn-outline-secondary btn-sm" onClick={() => navigate({ is_active: '' })}>Clear</button>
                    </div>
                )}
            </div>
            <DataTable columns={columns} data={items.data as Record<string, unknown>[]} pagination={items.meta} links={items.links} sortBy="" onSort={() => {}} onPageChange={handlePageChange}
                actions={(row) => (<div className="d-flex gap-1 justify-content-end"><Link href={`/admin/cassava-units/${row.id}/edit`} className="btn btn-outline-secondary btn-sm">Edit</Link><button onClick={() => setDeleting(row as unknown as CassavaUnit)} className="btn btn-outline-danger btn-sm">Delete</button></div>)} />
            <ConfirmDialog open={!!deleting} title="Delete cassava unit" message={`Delete "${deleting?.label}"? This cannot be undone.`} onConfirm={handleDelete} onCancel={() => setDeleting(null)} processing={processing} />
        </AdminLayout>
    )
}
