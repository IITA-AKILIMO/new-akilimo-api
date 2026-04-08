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
        <div
            className={`px-6 py-3 text-sm font-medium ${
                flash.success
                    ? 'bg-green-50 text-green-800 border-b border-green-200'
                    : 'bg-red-50 text-red-800 border-b border-red-200'
            }`}
        >
            {flash.success ?? flash.error}
        </div>
    )
}

interface AdminLayoutProps {
    title?: string
    children: ReactNode
}

export default function AdminLayout({ title, children }: AdminLayoutProps) {
    const { auth, url } = usePage<PageProps & { url: string }>().props

    function handleLogout() {
        router.post('/admin/logout')
    }

    const currentPath = typeof window !== 'undefined' ? window.location.pathname : ''

    return (
        <div className="flex h-screen overflow-hidden bg-gray-50">
            {/* Sidebar */}
            <aside className="flex w-64 flex-shrink-0 flex-col bg-gray-900 text-white">
                {/* Brand */}
                <div className="flex h-16 items-center px-6 border-b border-gray-700">
                    <span className="text-lg font-bold tracking-tight text-green-400">Akilimo</span>
                    <span className="ml-1 text-lg font-light text-gray-300">Admin</span>
                </div>

                {/* Navigation */}
                <nav className="flex-1 overflow-y-auto py-4 px-3">
                    {navigation.map((group) => (
                        <div key={group.heading} className="mb-6">
                            <p className="mb-1 px-3 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                {group.heading}
                            </p>
                            {group.items.map((item) => {
                                const active = currentPath === item.href || currentPath.startsWith(item.href + '/')
                                return (
                                    <Link
                                        key={item.href}
                                        href={item.href}
                                        className={`flex items-center rounded-md px-3 py-2 text-sm font-medium transition-colors ${
                                            active
                                                ? 'bg-green-700 text-white'
                                                : 'text-gray-400 hover:bg-gray-800 hover:text-white'
                                        }`}
                                    >
                                        {item.label}
                                    </Link>
                                )
                            })}
                        </div>
                    ))}
                </nav>

                {/* User info */}
                <div className="border-t border-gray-700 p-4">
                    <div className="flex items-center gap-3">
                        <div className="flex h-8 w-8 items-center justify-center rounded-full bg-green-600 text-sm font-bold text-white">
                            {auth.user?.name?.charAt(0).toUpperCase()}
                        </div>
                        <div className="min-w-0 flex-1">
                            <p className="truncate text-sm font-medium text-white">{auth.user?.name}</p>
                            <p className="truncate text-xs text-gray-400">{auth.user?.username}</p>
                        </div>
                        <button
                            onClick={handleLogout}
                            className="rounded p-1 text-gray-400 hover:text-white transition-colors"
                            title="Sign out"
                        >
                            <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                            </svg>
                        </button>
                    </div>
                </div>
            </aside>

            {/* Main content */}
            <div className="flex flex-1 flex-col overflow-hidden">
                {/* Top bar */}
                <header className="flex h-16 flex-shrink-0 items-center border-b border-gray-200 bg-white px-6">
                    <h1 className="text-lg font-semibold text-gray-800">{title ?? 'Dashboard'}</h1>
                </header>

                <FlashBanner />

                <main className="flex-1 overflow-y-auto p-6">
                    {children}
                </main>
            </div>
        </div>
    )
}
