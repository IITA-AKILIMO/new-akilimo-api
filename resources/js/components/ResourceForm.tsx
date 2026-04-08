import type { FormEvent, ReactNode } from 'react'

interface ResourceFormProps {
    title: string
    onSubmit: (e: FormEvent) => void
    processing: boolean
    onCancel: () => void
    children: ReactNode
}

export default function ResourceForm({ title, onSubmit, processing, onCancel, children }: ResourceFormProps) {
    return (
        <div className="rounded-xl border border-gray-200 bg-white shadow-sm">
            {/* Header */}
            <div className="border-b border-gray-100 px-6 py-4">
                <h2 className="text-base font-semibold text-gray-800">{title}</h2>
            </div>

            {/* Body */}
            <form onSubmit={onSubmit} noValidate>
                <div className="px-6 py-5 space-y-5">
                    {children}
                </div>

                {/* Footer */}
                <div className="flex items-center justify-end gap-3 border-t border-gray-100 px-6 py-4">
                    <button
                        type="button"
                        onClick={onCancel}
                        disabled={processing}
                        className="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300/50 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        disabled={processing}
                        className="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500/40 disabled:cursor-not-allowed disabled:opacity-70"
                    >
                        {processing && (
                            <svg
                                className="h-4 w-4 animate-spin"
                                viewBox="0 0 24 24"
                                fill="none"
                            >
                                <circle
                                    className="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    strokeWidth="4"
                                />
                                <path
                                    className="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
                                />
                            </svg>
                        )}
                        {processing ? 'Saving…' : 'Save'}
                    </button>
                </div>
            </form>
        </div>
    )
}
