import { Link, router } from '@inertiajs/react'
import { useState } from 'react'
import Badge from '../../components/Badge'
import ConfirmDialog from '../../components/ConfirmDialog'
import CountryFilter from '../../components/CountryFilter'
import DataTable, { type Column } from '../../components/DataTable'
import AdminLayout from '../../layouts/AdminLayout'
import type { Fertilizer, Paginated } from '../../types'

interface Filters { country: string; use_case: string; available: string }
interface Props { items: Paginated<Fertilizer>; filters: Filters }

export default function FertilizersIndex({ items, filters }: Props) {
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

    function navigate(next: Partial<Filters>) {
        router.get('/admin/fertilizers', { ...filters, ...next, page: 1 }, { preserveState: true })
    }

    function handlePageChange(page: number) {
        router.get('/admin/fertilizers', { ...filters, page }, { preserveState: true })
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

            <div className="row g-2 mb-3">
                <div className="col-auto">
                    <CountryFilter value={filters.country} onChange={(v) => navigate({ country: v })} />
                </div>
                <div className="col-auto">
                    <select className="form-select form-select-sm" value={filters.use_case} onChange={(e) => navigate({ use_case: e.target.value })}>
                        <option value="">All Use Cases</option>
                        <option value="CIS">CIS</option>
                        <option value="CIM">CIM</option>
                    </select>
                </div>
                <div className="col-auto">
                    <select className="form-select form-select-sm" value={filters.available} onChange={(e) => navigate({ available: e.target.value })}>
                        <option value="">All Availability</option>
                        <option value="1">Available</option>
                        <option value="0">Unavailable</option>
                    </select>
                </div>
                {(filters.country || filters.use_case || filters.available) && (
                    <div className="col-auto">
                        <button className="btn btn-outline-secondary btn-sm" onClick={() => navigate({ country: '', use_case: '', available: '' })}>Clear</button>
                    </div>
                )}
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
