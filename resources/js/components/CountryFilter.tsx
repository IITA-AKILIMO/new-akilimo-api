const COUNTRIES = [
    { code: 'ET', name: 'Ethiopia' },
    { code: 'GH', name: 'Ghana' },
    { code: 'KE', name: 'Kenya' },
    { code: 'MZ', name: 'Mozambique' },
    { code: 'NG', name: 'Nigeria' },
    { code: 'RW', name: 'Rwanda' },
    { code: 'TZ', name: 'Tanzania' },
    { code: 'UG', name: 'Uganda' },
]

interface CountryFilterProps {
    value: string
    onChange: (code: string) => void
}

export default function CountryFilter({ value, onChange }: CountryFilterProps) {
    return (
        <div className="flex items-center gap-2">
            <label htmlFor="country-filter" className="text-sm font-medium text-gray-600 whitespace-nowrap">
                Country
            </label>
            <div className="relative">
                <select
                    id="country-filter"
                    value={value}
                    onChange={(e) => onChange(e.target.value)}
                    className="appearance-none rounded-lg border border-gray-200 bg-white py-1.5 pl-3 pr-8 text-sm text-gray-700 shadow-sm outline-none transition focus:border-green-500 focus:ring-2 focus:ring-green-500/20"
                >
                    <option value="">All Countries</option>
                    {COUNTRIES.map((c) => (
                        <option key={c.code} value={c.code}>
                            {c.code} — {c.name}
                        </option>
                    ))}
                </select>
                <div className="pointer-events-none absolute inset-y-0 right-2.5 flex items-center">
                    <svg className="h-3.5 w-3.5 text-gray-400" viewBox="0 0 16 16" fill="currentColor">
                        <path d="M4.427 6.427a.75.75 0 0 1 1.06 0L8 8.94l2.513-2.513a.75.75 0 1 1 1.06 1.06l-3.043 3.044a.75.75 0 0 1-1.06 0L4.427 7.487a.75.75 0 0 1 0-1.06z" />
                    </svg>
                </div>
            </div>
        </div>
    )
}
