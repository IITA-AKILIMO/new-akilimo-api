import { Link, router, usePage } from '@inertiajs/react'
import type { ReactNode } from 'react'
import type { PageProps } from '../types'

interface NavItem {
    label: string
    href: string
}

interface NavGroup {
    heading: string
    items: NavItem[]
}

const navigation: NavGroup[] = [
    {
        heading: 'Overview',
        items: [
            { label: 'Dashboard', href: '/admin' },
        ],
    },
    {
        heading: 'Management',
        items: [
            { label: 'Users', href: '/admin/users' },
            { label: 'API Keys', href: '/admin/api-keys' },
            { label: 'Fertilizers', href: '/admin/fertilizers' },
            { label: 'Fertilizer Prices', href: '/admin/fertilizer-prices' },
        ],
    },
    {
        heading: 'Commodity Prices',
        items: [
            { label: 'Maize Prices', href: '/admin/maize-prices' },
            { label: 'Cassava Prices', href: '/admin/cassava-prices' },
            { label: 'Potato Prices', href: '/admin/potato-prices' },
            { label: 'Starch Prices', href: '/admin/starch-prices' },
            { label: 'Default Prices', href: '/admin/default-prices' },
        ],
    },
    {
        heading: 'Supporting Data',
        items: [
            { label: 'Investment Amounts', href: '/admin/investment-amounts' },
            { label: 'Operation Costs', href: '/admin/operation-costs' },
            { label: 'Starch Factories', href: '/admin/starch-factories' },
            { label: 'Countries', href: '/admin/countries' },
            { label: 'Currencies', href: '/admin/currencies' },
            { label: 'Cassava Units', href: '/admin/cassava-units' },
            { label: 'Translations', href: '/admin/translations' },
        ],
    },
    {
        heading: 'Monitoring',
        items: [
            { label: 'Request Log', href: '/admin/requests' },
            { label: 'User Feedback', href: '/admin/feedback' },
        ],
    },
]

function FlashBanner() {
    const { flash } = usePage<PageProps>().props

    if (!flash.success && !flash.error) return null

    return (
        <div className={flash.success ? 'flash-success' : 'flash-error'}>
            {flash.success ?? flash.error}
        </div>
    )
}

interface AdminLayoutProps {
    title?: string
    children: ReactNode
}

export default function AdminLayout({ title, children }: AdminLayoutProps) {
    const { auth } = usePage<PageProps>().props

    function handleLogout() {
        router.post('/admin/logout')
    }

    const currentPath = typeof window !== 'undefined' ? window.location.pathname : ''

    return (
        <div className="d-flex vh-100 overflow-hidden">
            {/* Sidebar */}
            <aside className="admin-sidebar d-flex flex-column bg-dark text-white flex-shrink-0">
                {/* Brand */}
                <div className="px-3 py-3 border-bottom border-secondary">
                    <span className="fs-5 fw-bold text-success">Akilimo</span>
                    <span className="fs-5 fw-light text-white-50 ms-1">Admin</span>
                </div>

                {/* Navigation */}
                <nav className="flex-grow-1 overflow-auto py-3 px-2">
                    {navigation.map((group) => (
                        <div key={group.heading} className="mb-4">
                            <div className="nav-group-label mb-1">{group.heading}</div>
                            {group.items.map((item) => {
                                const active = currentPath === item.href || currentPath.startsWith(item.href + '/')
                                return (
                                    <Link
                                        key={item.href}
                                        href={item.href}
                                        className={`nav-link${active ? ' active' : ''}`}
                                    >
                                        {item.label}
                                    </Link>
                                )
                            })}
                        </div>
                    ))}
                </nav>

                {/* User footer */}
                <div className="border-top border-secondary p-3">
                    <div className="d-flex align-items-center gap-2">
                        <div
                            className="d-flex align-items-center justify-content-center rounded-circle bg-success text-white fw-bold flex-shrink-0"
                            style={{ width: 34, height: 34, fontSize: '0.85rem' }}
                        >
                            {auth.user?.name?.charAt(0).toUpperCase()}
                        </div>
                        <div className="flex-grow-1 overflow-hidden">
                            <div className="text-white text-truncate small fw-medium">{auth.user?.name}</div>
                            <div className="text-white-50 text-truncate" style={{ fontSize: '0.75rem' }}>{auth.user?.username}</div>
                        </div>
                        <button
                            onClick={handleLogout}
                            className="btn btn-link p-1 text-white-50 text-decoration-none"
                            title="Sign out"
                        >
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                            </svg>
                        </button>
                    </div>
                </div>
            </aside>

            {/* Main area */}
            <div className="d-flex flex-column flex-grow-1 overflow-hidden">
                {/* Top bar */}
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
