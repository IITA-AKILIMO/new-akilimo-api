import type { ReactNode } from 'react'

interface FormFieldProps {
    label: string
    error?: string
    required?: boolean
    children: ReactNode
}

export default function FormField({ label, error, required, children }: FormFieldProps) {
    return (
        <div className="space-y-1.5">
            <label className="block text-sm font-medium text-gray-700">
                {label}
                {required && (
                    <span className="ml-1 text-red-500" aria-hidden="true">
                        *
                    </span>
                )}
            </label>

            <div className={error ? '[&_input]:border-red-400 [&_select]:border-red-400 [&_textarea]:border-red-400' : ''}>
                {children}
            </div>

            {error && (
                <p className="flex items-center gap-1 text-xs text-red-600">
                    <svg className="h-3.5 w-3.5 flex-shrink-0" viewBox="0 0 16 16" fill="currentColor">
                        <path d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zm.75 4.5v3.5a.75.75 0 0 1-1.5 0V5.5a.75.75 0 0 1 1.5 0zm0 6a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0z" />
                    </svg>
                    {error}
                </p>
            )}
        </div>
    )
}
