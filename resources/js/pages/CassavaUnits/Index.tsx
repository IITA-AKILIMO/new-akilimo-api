import { Link, router } from '@inertiajs/react'
import { useState } from 'react'
import Badge from '../../components/Badge'
import ConfirmDialog from '../../components/ConfirmDialog'
import DataTable, { type Column } from '../../components/DataTable'
import AdminLayout from '../../layouts/AdminLayout'
import type { CassavaUnit, Paginated } from '../../types'

interface Props { items: Paginated<CassavaUnit> }

export default function CassavaUnitsIndex({ items }: Props) {
    const [deleting, setDeleting] = useState<CassavaUnit | null>(null)
    const [processing, setProcessing] = useState(false)
    const columns: Column[] = [
        { key: 'label', label: 'Label' },
        { key: 'unit_weight', label: 'Weight (kg)' },
        { key: 'description', label: 'Description' },
        { key: 'is_active', label: 'Active', render: (v) => <Badge active={!!v} /> },
    ]
    function handlePageChange(page: number) { router.get('/admin/cassava-units', { page }, { preserveState: true }) }
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
            <DataTable columns={columns} data={items.data as Record<string, unknown>[]} pagination={items.meta} links={items.links} sortBy="" onSort={() => {}} onPageChange={handlePageChange}
                actions={(row) => (<div className="d-flex gap-1 justify-content-end"><Link href={`/admin/cassava-units/${row.id}/edit`} className="btn btn-outline-secondary btn-sm">Edit</Link><button onClick={() => setDeleting(row as unknown as CassavaUnit)} className="btn btn-outline-danger btn-sm">Delete</button></div>)} />
            <ConfirmDialog open={!!deleting} title="Delete cassava unit" message={`Delete "${deleting?.label}"? This cannot be undone.`} onConfirm={handleDelete} onCancel={() => setDeleting(null)} processing={processing} />
        </AdminLayout>
    )
}
