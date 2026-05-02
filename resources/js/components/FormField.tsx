import type { ReactNode } from 'react'

interface FormFieldProps {
    label: string
    error?: string
    required?: boolean
    children: ReactNode
}

export default function FormField({ label, error, required, children }: FormFieldProps) {
    return (
        <div className="mb-3">
            <label className="form-label fw-medium">
                {label}
                {required && <span className="text-danger ms-1" aria-hidden="true">*</span>}
            </label>

            <div className={error ? 'is-invalid-wrap' : ''}>
                {children}
            </div>

            {error && (
                <div className="text-danger small mt-1">{error}</div>
            )}
        </div>
    )
}
