import type { ReactNode } from 'react'
import FlashBanner from '../components/FlashBanner'
import Sidebar from '../components/Sidebar'

interface AdminLayoutProps {
    title?: string
    children: ReactNode
}

export default function AdminLayout({ title, children }: AdminLayoutProps) {
    const currentPath = typeof window !== 'undefined' ? window.location.pathname : ''

    return (
        <div className="d-flex vh-100 overflow-hidden">
            <Sidebar currentPath={currentPath} />

            <div className="d-flex flex-column flex-grow-1 overflow-hidden">
                <header className="d-flex align-items-center border-bottom bg-white px-4" style={{ minHeight: 60 }}>
                    <h1 className="h5 mb-0 fw-semibold">{title ?? 'Dashboard'}</h1>
                </header>

                <FlashBanner />

                <main className="flex-grow-1 overflow-auto p-4">
                    {children}
                </main>
            </div>
        </div>
    )
}
