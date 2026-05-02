import { Link, router } from '@inertiajs/react'
import { useState } from 'react'
import ConfirmDialog from '../../components/ConfirmDialog'
import DataTable from '../../components/DataTable'
import type { Column } from '../../components/DataTable'
import AdminLayout from '../../layouts/AdminLayout'
import type { Paginated, User } from '../../types'

interface Props {
    users: Paginated<User>
    filters: {
        sort_by: string
        sort_dir: 'asc' | 'desc'
        search: string
    }
}

export default function UsersIndex({ users, filters }: Props) {
    const [deleting, setDeleting] = useState<User | null>(null)
    const [processing, setProcessing] = useState(false)
    const [searchInput, setSearchInput] = useState(filters.search)

    const columns: Column[] = [
        { key: 'id', label: 'ID', sortable: true },
        { key: 'name', label: 'Name', sortable: true },
        { key: 'username', label: 'Username', sortable: true },
        { key: 'email', label: 'Email', sortable: true },
        {
            key: 'created_at',
            label: 'Created',
            sortable: true,
            render: (val) =>
                val ? new Date(val as string).toLocaleDateString() : '—',
        },
    ]

    function handleSort(col: string) {
        const dir =
            filters.sort_by === col && filters.sort_dir === 'asc' ? 'desc' : 'asc'
        router.get('/admin/users', { ...filters, sort_by: col, sort_dir: dir, page: 1 }, { preserveState: true })
    }

    function handlePageChange(page: number) {
        router.get('/admin/users', { ...filters, page }, { preserveState: true })
    }

    function navigateSearch(search: string) {
        router.get('/admin/users', { ...filters, search, page: 1 }, { preserveState: true })
    }

    function handleDelete() {
        if (!deleting) return
        setProcessing(true)
        router.delete(`/admin/users/${deleting.id}`, {
            onFinish: () => {
                setProcessing(false)
                setDeleting(null)
            },
        })
    }

    return (
        <AdminLayout title="Users">
            <div className="d-flex align-items-center justify-content-between mb-3">
                <span className="text-muted small">
                    {users.meta.total} user{users.meta.total !== 1 ? 's' : ''} total
                </span>
                <Link href="/admin/users/create" className="btn btn-success btn-sm">
                    + New User
                </Link>
            </div>

            <div className="row g-2 mb-3">
                <div className="col-auto">
                    <input
                        type="text" className="form-control form-control-sm" placeholder="Search name, username or email…"
                        value={searchInput} onChange={(e) => setSearchInput(e.target.value)}
                        onKeyDown={(e) => { if (e.key === 'Enter') navigateSearch(searchInput) }}
                        onBlur={() => { if (searchInput !== filters.search) navigateSearch(searchInput) }}
                        style={{ width: 280 }}
                    />
                </div>
                {filters.search && (
                    <div className="col-auto">
                        <button className="btn btn-outline-secondary btn-sm" onClick={() => { setSearchInput(''); navigateSearch('') }}>Clear</button>
                    </div>
                )}
            </div>

            <DataTable
                columns={columns}
                data={users.data as Record<string, unknown>[]}
                pagination={users.meta}
                links={users.links}
                sortBy={filters.sort_by}
                sortDir={filters.sort_dir}
                onSort={handleSort}
                onPageChange={handlePageChange}
                actions={(row) => (
                    <div className="d-flex align-items-center justify-content-end gap-1">
                        <Link
                            href={`/admin/users/${row.id}/edit`}
                            className="btn btn-outline-secondary btn-sm"
                        >
                            Edit
                        </Link>
                        <button
                            onClick={() => setDeleting(row as unknown as User)}
                            className="btn btn-outline-danger btn-sm"
                        >
                            Delete
                        </button>
                    </div>
                )}
            />

            <ConfirmDialog
                open={deleting !== null}
                title="Delete user"
                message={`Are you sure you want to delete "${deleting?.name}"? This cannot be undone.`}
                onConfirm={handleDelete}
                onCancel={() => setDeleting(null)}
                processing={processing}
            />
        </AdminLayout>
    )
}
