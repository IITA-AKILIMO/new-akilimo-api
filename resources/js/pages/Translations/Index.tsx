import { Link, router } from '@inertiajs/react'
import { useState } from 'react'
import ConfirmDialog from '../../components/ConfirmDialog'
import DataTable, { type Column } from '../../components/DataTable'
import AdminLayout from '../../layouts/AdminLayout'
import type { Paginated, Translation } from '../../types'

interface Filters { search: string }
interface Props { items: Paginated<Translation>; filters: Filters }

export default function TranslationsIndex({ items, filters }: Props) {
    const [deleting, setDeleting] = useState<Translation | null>(null)
    const [processing, setProcessing] = useState(false)
    const [searchInput, setSearchInput] = useState(filters.search)

    const columns: Column[] = [
        { key: 'key', label: 'Key' },
        { key: 'en', label: 'English', render: (v) => <span className="text-truncate d-inline-block" style={{ maxWidth: 200 }}>{String(v ?? '')}</span> },
        { key: 'sw', label: 'Swahili' },
        { key: 'rw', label: 'Kinyarwanda' },
    ]

    function navigate(next: Partial<Filters>) {
        router.get('/admin/translations', { ...filters, ...next, page: 1 }, { preserveState: true })
    }

    function handlePageChange(page: number) { router.get('/admin/translations', { ...filters, page }, { preserveState: true }) }

    function handleDelete() {
        if (!deleting) return; setProcessing(true)
        router.delete(`/admin/translations/${deleting.id}`, { onFinish: () => { setProcessing(false); setDeleting(null) } })
    }

    return (
        <AdminLayout title="Translations">
            <div className="d-flex align-items-center justify-content-between mb-3">
                <span className="text-muted small">{items.meta.total} records</span>
                <div className="d-flex gap-2">
                    <Link href="/admin/translations/batch-edit" className="btn btn-outline-secondary btn-sm">Batch Edit</Link>
                    <Link href="/admin/translations/create" className="btn btn-success btn-sm">+ New</Link>
                </div>
            </div>
            <div className="row g-2 mb-3">
                <div className="col-auto">
                    <input
                        type="text" className="form-control form-control-sm" placeholder="Search by key…"
                        value={searchInput} onChange={(e) => setSearchInput(e.target.value)}
                        onKeyDown={(e) => { if (e.key === 'Enter') navigate({ search: searchInput }) }}
                        onBlur={() => { if (searchInput !== filters.search) navigate({ search: searchInput }) }}
                        style={{ width: 240 }}
                    />
                </div>
                {filters.search && (
                    <div className="col-auto">
                        <button className="btn btn-outline-secondary btn-sm" onClick={() => { setSearchInput(''); navigate({ search: '' }) }}>Clear</button>
                    </div>
                )}
            </div>
            <DataTable columns={columns} data={items.data as Record<string, unknown>[]} pagination={items.meta} links={items.links} sortBy="" onSort={() => {}} onPageChange={handlePageChange}
                actions={(row) => (<div className="d-flex gap-1 justify-content-end"><Link href={`/admin/translations/${row.id}/edit`} className="btn btn-outline-secondary btn-sm">Edit</Link><button onClick={() => setDeleting(row as unknown as Translation)} className="btn btn-outline-danger btn-sm">Delete</button></div>)} />
            <ConfirmDialog open={!!deleting} title="Delete translation" message={`Delete key "${deleting?.key}"? This cannot be undone.`} onConfirm={handleDelete} onCancel={() => setDeleting(null)} processing={processing} />
        </AdminLayout>
    )
}
