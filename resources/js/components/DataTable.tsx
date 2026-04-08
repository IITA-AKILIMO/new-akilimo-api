import type { ReactNode } from 'react'
import type { PaginationLinks, PaginationMeta } from '../types'
import Pagination from './Pagination'

export interface Column {
    key: string
    label: string
    sortable?: boolean
    render?: (value: unknown, row: Record<string, unknown>) => ReactNode
}

interface DataTableProps {
    columns: Column[]
    data: Record<string, unknown>[]
    pagination: PaginationMeta
    links: PaginationLinks
    sortBy?: string
    sortDir?: 'asc' | 'desc'
    onSort: (col: string) => void
    onPageChange: (page: number) => void
    actions?: (row: Record<string, unknown>) => ReactNode
}

function SortIcon({ active, dir }: { active: boolean; dir: 'asc' | 'desc' }) {
    return (
        <span className={`ml-1.5 inline-flex flex-col gap-px ${active ? 'text-green-600' : 'text-gray-300'}`}>
            <svg
                className={`h-2.5 w-2.5 transition-opacity ${active && dir === 'asc' ? 'opacity-100' : 'opacity-40'}`}
                viewBox="0 0 10 6"
                fill="currentColor"
            >
                <path d="M5 0L9.33 6H.67L5 0z" />
            </svg>
            <svg
                className={`h-2.5 w-2.5 transition-opacity ${active && dir === 'desc' ? 'opacity-100' : 'opacity-40'}`}
                viewBox="0 0 10 6"
                fill="currentColor"
            >
                <path d="M5 6L.67 0h8.66L5 6z" />
            </svg>
        </span>
    )
}

export default function DataTable({
    columns,
    data,
    pagination,
    links,
    sortBy,
    sortDir = 'asc',
    onSort,
    onPageChange,
    actions,
}: DataTableProps) {
    return (
        <div className="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            {/* Table */}
            <div className="overflow-x-auto">
                <table className="min-w-full divide-y divide-gray-200 text-sm">
                    <thead className="bg-gray-50">
                        <tr>
                            {columns.map((col) => (
                                <th
                                    key={col.key}
                                    scope="col"
                                    className={`px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 ${
                                        col.sortable
                                            ? 'cursor-pointer select-none hover:text-gray-700'
                                            : ''
                                    }`}
                                    onClick={col.sortable ? () => onSort(col.key) : undefined}
                                >
                                    <span className="inline-flex items-center">
                                        {col.label}
                                        {col.sortable && (
                                            <SortIcon
                                                active={sortBy === col.key}
                                                dir={sortBy === col.key ? sortDir : 'asc'}
                                            />
                                        )}
                                    </span>
                                </th>
                            ))}
                            {actions && (
                                <th scope="col" className="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Actions
                                </th>
                            )}
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-gray-100">
                        {data.length === 0 ? (
                            <tr>
                                <td
                                    colSpan={columns.length + (actions ? 1 : 0)}
                                    className="py-16 text-center text-sm text-gray-400"
                                >
                                    <div className="flex flex-col items-center gap-2">
                                        <svg className="h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        No records found
                                    </div>
                                </td>
                            </tr>
                        ) : (
                            data.map((row, rowIdx) => (
                                <tr
                                    key={rowIdx}
                                    className={`transition-colors hover:bg-green-50/40 ${
                                        rowIdx % 2 === 0 ? 'bg-white' : 'bg-gray-50/60'
                                    }`}
                                >
                                    {columns.map((col) => (
                                        <td key={col.key} className="whitespace-nowrap px-4 py-3 text-gray-700">
                                            {col.render
                                                ? col.render(row[col.key], row)
                                                : String(row[col.key] ?? '—')}
                                        </td>
                                    ))}
                                    {actions && (
                                        <td className="whitespace-nowrap px-4 py-3 text-right">
                                            {actions(row)}
                                        </td>
                                    )}
                                </tr>
                            ))
                        )}
                    </tbody>
                </table>
            </div>

            {/* Pagination */}
            {pagination.last_page > 1 && (
                <div className="border-t border-gray-100 px-4 py-3">
                    <Pagination meta={pagination} links={links} onPageChange={onPageChange} />
                </div>
            )}
        </div>
    )
}
