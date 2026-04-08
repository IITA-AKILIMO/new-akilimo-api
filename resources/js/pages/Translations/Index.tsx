import { Link, router } from '@inertiajs/react'
import { useState } from 'react'
import ConfirmDialog from '../../components/ConfirmDialog'
import DataTable, { type Column } from '../../components/DataTable'
import AdminLayout from '../../layouts/AdminLayout'
import type { Paginated, Translation } from '../../types'

interface Props { items: Paginated<Translation> }

export default function TranslationsIndex({ items }: Props) {
    const [deleting, setDeleting] = useState<Translation | null>(null)
    const [processing, setProcessing] = useState(false)
    const columns: Column[] = [
        { key: 'key', label: 'Key' },
        { key: 'en', label: 'English', render: (v) => <span className="text-truncate d-inline-block" style={{ maxWidth: 200 }}>{String(v ?? '')}</span> },
        { key: 'sw', label: 'Swahili' },
        { key: 'rw', label: 'Kinyarwanda' },
    ]
    function handlePageChange(page: number) { router.get('/admin/translations', { page }, { preserveState: true }) }
    function handleDelete() {
        if (!deleting) return; setProcessing(true)
        router.delete(`/admin/translations/${deleting.id}`, { onFinish: () => { setProcessing(false); setDeleting(null) } })
    }
    return (
        <AdminLayout title="Translations">
            <div className="d-flex align-items-center justify-content-between mb-3">
                <span className="text-muted small">{items.meta.total} records</span>
                <Link href="/admin/translations/create" className="btn btn-success btn-sm">+ New</Link>
            </div>
            <DataTable columns={columns} data={items.data as Record<string, unknown>[]} pagination={items.meta} links={items.links} sortBy="" onSort={() => {}} onPageChange={handlePageChange}
                actions={(row) => (<div className="d-flex gap-1 justify-content-end"><Link href={`/admin/translations/${row.id}/edit`} className="btn btn-outline-secondary btn-sm">Edit</Link><button onClick={() => setDeleting(row as unknown as Translation)} className="btn btn-outline-danger btn-sm">Delete</button></div>)} />
            <ConfirmDialog open={!!deleting} title="Delete translation" message={`Delete key "${deleting?.key}"? This cannot be undone.`} onConfirm={handleDelete} onCancel={() => setDeleting(null)} processing={processing} />
        </AdminLayout>
    )
}
