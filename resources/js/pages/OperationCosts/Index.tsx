import { Link, router } from '@inertiajs/react'
import { useState } from 'react'
import Badge from '../../components/Badge'
import ConfirmDialog from '../../components/ConfirmDialog'
import CountryFilter from '../../components/CountryFilter'
import DataTable, { type Column } from '../../components/DataTable'
import AdminLayout from '../../layouts/AdminLayout'
import type { OperationCost, Paginated } from '../../types'

interface Filters { country: string }
interface Props { items: Paginated<OperationCost>; filters: Filters }

export default function OperationCostsIndex({ items, filters }: Props) {
    const [deleting, setDeleting] = useState<OperationCost | null>(null)
    const [processing, setProcessing] = useState(false)
    const columns: Column[] = [
        { key: 'operation_name', label: 'Operation' },
        { key: 'operation_type', label: 'Type' },
        { key: 'country_code', label: 'Country' },
        { key: 'min_cost', label: 'Min Cost' },
        { key: 'max_cost', label: 'Max Cost' },
        { key: 'is_active', label: 'Active', render: (v) => <Badge active={!!v} /> },
    ]
    function navigate(next: Partial<Filters>) {
        router.get('/admin/operation-costs', { ...filters, ...next, page: 1 }, { preserveState: true })
    }
    function handlePageChange(page: number) { router.get('/admin/operation-costs', { ...filters, page }, { preserveState: true }) }
    function handleDelete() {
        if (!deleting) return; setProcessing(true)
        router.delete(`/admin/operation-costs/${deleting.id}`, { onFinish: () => { setProcessing(false); setDeleting(null) } })
    }
    return (
        <AdminLayout title="Operation Costs">
            <div className="d-flex align-items-center justify-content-between mb-3">
                <span className="text-muted small">{items.meta.total} records</span>
                <div className="d-flex gap-2">
                    <Link href="/admin/operation-costs/batch-edit" className="btn btn-outline-secondary btn-sm">Batch Edit</Link>
                    <Link href="/admin/operation-costs/batch-create" className="btn btn-outline-success btn-sm">Batch Add</Link>
                    <Link href="/admin/operation-costs/create" className="btn btn-success btn-sm">+ New</Link>
                </div>
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
                actions={(row) => (<div className="d-flex gap-1 justify-content-end"><Link href={`/admin/operation-costs/${row.id}/edit`} className="btn btn-outline-secondary btn-sm">Edit</Link><button onClick={() => setDeleting(row as unknown as OperationCost)} className="btn btn-outline-danger btn-sm">Delete</button></div>)} />
            <ConfirmDialog open={!!deleting} title="Delete operation cost" message={`Delete "${deleting?.operation_name}"? This cannot be undone.`} onConfirm={handleDelete} onCancel={() => setDeleting(null)} processing={processing} />
        </AdminLayout>
    )
}
