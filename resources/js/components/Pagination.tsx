import type { PaginationLinks, PaginationMeta } from '../types'

interface PaginationProps {
    meta: PaginationMeta
    links: PaginationLinks
    onPageChange: (page: number) => void
}

function buildPageRange(current: number, last: number): (number | '…')[] {
    if (last <= 7) return Array.from({ length: last }, (_, i) => i + 1)

    const pages: (number | '…')[] = []

    if (current <= 4) {
        pages.push(1, 2, 3, 4, 5, '…', last)
    } else if (current >= last - 3) {
        pages.push(1, '…', last - 4, last - 3, last - 2, last - 1, last)
    } else {
        pages.push(1, '…', current - 1, current, current + 1, '…', last)
    }

    return pages
}

export default function Pagination({ meta, onPageChange }: PaginationProps) {
    const { current_page, last_page, per_page, total, from, to } = meta
    const pages = buildPageRange(current_page, last_page)

    const btnBase =
        'flex h-8 min-w-[2rem] items-center justify-center rounded-md px-2.5 text-sm font-medium transition-colors focus:outline-none'
    const btnActive = 'bg-green-600 text-white shadow-sm'
    const btnIdle = 'text-gray-600 hover:bg-gray-100'
    const btnDisabled = 'cursor-not-allowed text-gray-300'

    return (
        <div className="flex flex-col items-center gap-3 sm:flex-row sm:justify-between">
            {/* Count */}
            <p className="text-sm text-gray-500">
                {from !== null && to !== null ? (
                    <>
                        Showing <span className="font-medium text-gray-700">{from}</span>–
                        <span className="font-medium text-gray-700">{to}</span> of{' '}
                        <span className="font-medium text-gray-700">{total}</span> results
                    </>
                ) : (
                    'No results'
                )}
            </p>

            {/* Controls */}
            <nav className="flex items-center gap-1" aria-label="Pagination">
                {/* Previous */}
                <button
                    onClick={() => onPageChange(current_page - 1)}
                    disabled={current_page === 1}
                    className={`${btnBase} gap-1 ${current_page === 1 ? btnDisabled : btnIdle}`}
                    aria-label="Previous page"
                >
                    <svg className="h-3.5 w-3.5" viewBox="0 0 16 16" fill="currentColor">
                        <path d="M9.78 12.78a.75.75 0 0 1-1.06 0L4.47 8.53a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 1.06L6.06 8l3.72 3.72a.75.75 0 0 1 0 1.06z" />
                    </svg>
                    <span className="hidden sm:inline">Prev</span>
                </button>

                {/* Page numbers */}
                {pages.map((page, i) =>
                    page === '…' ? (
                        <span key={`ellipsis-${i}`} className="px-1.5 text-sm text-gray-400">
                            …
                        </span>
                    ) : (
                        <button
                            key={page}
                            onClick={() => onPageChange(page as number)}
                            className={`${btnBase} ${page === current_page ? btnActive : btnIdle}`}
                            aria-current={page === current_page ? 'page' : undefined}
                        >
                            {page}
                        </button>
                    ),
                )}

                {/* Next */}
                <button
                    onClick={() => onPageChange(current_page + 1)}
                    disabled={current_page === last_page}
                    className={`${btnBase} gap-1 ${current_page === last_page ? btnDisabled : btnIdle}`}
                    aria-label="Next page"
                >
                    <span className="hidden sm:inline">Next</span>
                    <svg className="h-3.5 w-3.5" viewBox="0 0 16 16" fill="currentColor">
                        <path d="M6.22 3.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L9.94 8 6.22 4.28a.75.75 0 0 1 0-1.06z" />
                    </svg>
                </button>
            </nav>
        </div>
    )
}
