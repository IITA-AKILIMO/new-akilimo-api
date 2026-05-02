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
        <div className="card shadow-sm">
            <div className="card-header bg-white py-3">
                <h5 className="mb-0 fw-semibold">{title}</h5>
            </div>

            <form onSubmit={onSubmit} noValidate>
                <div className="card-body">
                    {children}
                </div>

                <div className="card-footer bg-white d-flex justify-content-end gap-2 py-3">
                    <button
                        type="button"
                        onClick={onCancel}
                        disabled={processing}
                        className="btn btn-outline-secondary"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        disabled={processing}
                        className="btn btn-success"
                    >
                        {processing && (
                            <span
                                className="spinner-border spinner-border-sm me-2"
                                role="status"
                                aria-hidden="true"
                            />
                        )}
                        {processing ? 'Saving…' : 'Save'}
                    </button>
                </div>
            </form>
        </div>
    )
}
