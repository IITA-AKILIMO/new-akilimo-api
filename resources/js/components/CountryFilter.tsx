import { usePage } from '@inertiajs/react'
import type { PageProps } from '../types'

interface CountryFilterProps {
    value: string
    onChange: (code: string) => void
}

export default function CountryFilter({ value, onChange }: CountryFilterProps) {
    const { countries } = usePage<PageProps>().props

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
                {countries.map((c) => (
                    <option key={c.code} value={c.code}>
                        {c.code} — {c.name}
                    </option>
                ))}
            </select>
        </div>
    )
}
