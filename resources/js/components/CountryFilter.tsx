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
        <div className="d-flex align-items-center gap-2">
            <label htmlFor="country-filter" className="form-label mb-0 fw-medium text-nowrap">
                Country
            </label>
            <select
                id="country-filter"
                value={value}
                onChange={(e) => onChange(e.target.value)}
                className="form-select form-select-sm"
                style={{ width: 'auto' }}
            >
                <option value="">All Countries</option>
                {COUNTRIES.map((c) => (
                    <option key={c.code} value={c.code}>
                        {c.code} — {c.name}
                    </option>
                ))}
            </select>
        </div>
    )
}
