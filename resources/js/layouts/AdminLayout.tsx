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
                <header className="admin-header">
                    <div className="d-flex align-items-center gap-3">
                        <img src="/images/akilimo_logo_colored.png" alt="Akilimo" className="header-logo" />
                        <span className="header-logo-sep" aria-hidden="true" />
                        <h1 className="page-title">{title ?? 'Dashboard'}</h1>
                    </div>
                </header>

                <FlashBanner />

                <main className="flex-grow-1 overflow-auto p-4 bg-light">
                    {children}
                </main>
            </div>
        </div>
    )
}
