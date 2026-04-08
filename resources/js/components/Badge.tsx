interface BadgeProps {
    active: boolean
    activeLabel?: string
    inactiveLabel?: string
}

export default function Badge({
    active,
    activeLabel = 'Active',
    inactiveLabel = 'Inactive',
}: BadgeProps) {
    return (
        <span
            className={`inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium tracking-wide ${
                active
                    ? 'bg-green-50 text-green-700 ring-1 ring-green-600/20'
                    : 'bg-gray-100 text-gray-500 ring-1 ring-gray-500/10'
            }`}
        >
            <span
                className={`h-1.5 w-1.5 rounded-full ${active ? 'bg-green-500' : 'bg-gray-400'}`}
            />
            {active ? activeLabel : inactiveLabel}
        </span>
    )
}
