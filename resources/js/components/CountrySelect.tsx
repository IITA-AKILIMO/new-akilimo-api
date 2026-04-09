import { usePage } from '@inertiajs/react'
import type { PageProps } from '../types'

interface CountrySelectProps {
    value: string
    onChange: (code: string) => void
    /** Optional: fired with the full country name when the selection changes */
    onNameChange?: (name: string) => void
    error?: string
    required?: boolean
}

export default function CountrySelect({ value, onChange, onNameChange, error, required }: CountrySelectProps) {
    const { countries } = usePage<PageProps>().props

    function handleChange(code: string) {
        onChange(code)
        if (onNameChange) {
            onNameChange(countries.find((c) => c.code === code)?.name ?? '')
        }
    }

    return (
        <select
            value={value}
            onChange={(e) => handleChange(e.target.value)}
            className={`form-select${error ? ' is-invalid' : ''}`}
            required={required}
        >
            <option value="">Select country</option>
            {countries.map((c) => (
                <option key={c.code} value={c.code}>
                    {c.code} — {c.name}
                </option>
            ))}
        </select>
    )
}
