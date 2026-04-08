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
    }
}

export default function UsersIndex({ users, filters }: Props) {
    const [deleting, setDeleting] = useState<User | null>(null)
    const [processing, setProcessing] = useState(false)

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
        router.get('/admin/users', { sort_by: col, sort_dir: dir }, { preserveState: true })
    }

    function handlePageChange(page: number) {
        router.get('/admin/users', { ...filters, page }, { preserveState: true })
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
            <div className="space-y-4">
                {/* Toolbar */}
                <div className="flex items-center justify-between">
                    <p className="text-sm text-gray-500">
                        {users.meta.total} user{users.meta.total !== 1 ? 's' : ''} total
                    </p>
                    <Link
                        href="/admin/users/create"
                        className="inline-flex items-center gap-1.5 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 transition-colors"
                    >
                        <svg className="h-4 w-4" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M8 2a.75.75 0 0 1 .75.75v4.5h4.5a.75.75 0 0 1 0 1.5h-4.5v4.5a.75.75 0 0 1-1.5 0v-4.5h-4.5a.75.75 0 0 1 0-1.5h4.5v-4.5A.75.75 0 0 1 8 2z" />
                        </svg>
                        New User
                    </Link>
                </div>

                {/* Table */}
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
                        <div className="flex items-center justify-end gap-2">
                            <Link
                                href={`/admin/users/${row.id}/edit`}
                                className="rounded-md px-2.5 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-100 transition-colors"
                            >
                                Edit
                            </Link>
                            <button
                                onClick={() => setDeleting(row as unknown as User)}
                                className="rounded-md px-2.5 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50 transition-colors"
                            >
                                Delete
                            </button>
                        </div>
                    )}
                />
            </div>

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
