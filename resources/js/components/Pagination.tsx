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
    const { current_page, last_page, total, from, to } = meta
    const pages = buildPageRange(current_page, last_page)

    return (
        <div className="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2">
            <p className="text-muted small mb-0">
                {from !== null && to !== null ? (
                    <>Showing <strong>{from}</strong>–<strong>{to}</strong> of <strong>{total}</strong> results</>
                ) : (
                    'No results'
                )}
            </p>

            <nav aria-label="Pagination">
                <ul className="pagination pagination-sm mb-0">
                    <li className={`page-item ${current_page === 1 ? 'disabled' : ''}`}>
                        <button
                            className="page-link"
                            onClick={() => onPageChange(current_page - 1)}
                            disabled={current_page === 1}
                        >
                            &lsaquo; Prev
                        </button>
                    </li>

                    {pages.map((page, i) =>
                        page === '…' ? (
                            <li key={`ellipsis-${i}`} className="page-item disabled">
                                <span className="page-link">…</span>
                            </li>
                        ) : (
                            <li key={page} className={`page-item ${page === current_page ? 'active' : ''}`}>
                                <button
                                    className="page-link"
                                    onClick={() => onPageChange(page as number)}
                                    aria-current={page === current_page ? 'page' : undefined}
                                >
                                    {page}
                                </button>
                            </li>
                        ),
                    )}

                    <li className={`page-item ${current_page === last_page ? 'disabled' : ''}`}>
                        <button
                            className="page-link"
                            onClick={() => onPageChange(current_page + 1)}
                            disabled={current_page === last_page}
                        >
                            Next &rsaquo;
                        </button>
                    </li>
                </ul>
            </nav>
        </div>
    )
}
