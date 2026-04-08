import { Link, router } from '@inertiajs/react'
import { useState } from 'react'
import Badge from '../../components/Badge'
import ConfirmDialog from '../../components/ConfirmDialog'
import DataTable, { type Column } from '../../components/DataTable'
import AdminLayout from '../../layouts/AdminLayout'
import type { Fertilizer, Paginated } from '../../types'

interface Props { items: Paginated<Fertilizer> }

export default function FertilizersIndex({ items }: Props) {
    const [deleting, setDeleting] = useState<Fertilizer | null>(null)
    const [processing, setProcessing] = useState(false)

    const columns: Column[] = [
        { key: 'name', label: 'Name', sortable: false },
        { key: 'fertilizer_key', label: 'Key', sortable: false },
        { key: 'type', label: 'Type', sortable: false },
        { key: 'country', label: 'Country', sortable: false },
        { key: 'use_case', label: 'Use Case', sortable: false },
        { key: 'weight', label: 'Weight (kg)', sortable: false },
        { key: 'available', label: 'Available', sortable: false, render: (v) => <Badge active={!!v} /> },
    ]

    function handlePageChange(page: number) {
        router.get('/admin/fertilizers', { page }, { preserveState: true })
    }

    function handleDelete() {
        if (!deleting) return
        setProcessing(true)
        router.delete(`/admin/fertilizers/${deleting.id}`, {
            onFinish: () => { setProcessing(false); setDeleting(null) },
        })
    }

    return (
        <AdminLayout title="Fertilizers">
            <div className="d-flex align-items-center justify-content-between mb-3">
                <span className="text-muted small">{items.meta.total} records</span>
                <Link href="/admin/fertilizers/create" className="btn btn-success btn-sm">+ New</Link>
            </div>
            <DataTable columns={columns} data={items.data as Record<string, unknown>[]}
                pagination={items.meta} links={items.links} sortBy="" onSort={() => {}} onPageChange={handlePageChange}
                actions={(row) => (
                    <div className="d-flex gap-1 justify-content-end">
                        <Link href={`/admin/fertilizers/${row.id}/edit`} className="btn btn-outline-secondary btn-sm">Edit</Link>
                        <button onClick={() => setDeleting(row as unknown as Fertilizer)} className="btn btn-outline-danger btn-sm">Delete</button>
                    </div>
                )} />
            <ConfirmDialog open={!!deleting} title="Delete fertilizer"
                message={`Delete "${deleting?.name}"? This cannot be undone.`}
                onConfirm={handleDelete} onCancel={() => setDeleting(null)} processing={processing} />
        </AdminLayout>
    )
}
