import { useEffect } from 'react'

interface ConfirmDialogProps {
    open: boolean
    title: string
    message: string
    onConfirm: () => void
    onCancel: () => void
    processing?: boolean
}

export default function ConfirmDialog({
    open,
    title,
    message,
    onConfirm,
    onCancel,
    processing = false,
}: ConfirmDialogProps) {
    useEffect(() => {
        if (!open) return

        function handleKey(e: KeyboardEvent) {
            if (e.key === 'Escape') onCancel()
            if (e.key === 'Enter') onConfirm()
        }

        document.addEventListener('keydown', handleKey)
        return () => document.removeEventListener('keydown', handleKey)
    }, [open, onCancel, onConfirm])

    if (!open) return null

    return (
        <>
            {/* Backdrop */}
            <div
                className="modal-backdrop fade show"
                onClick={onCancel}
                style={{ cursor: 'default' }}
            />

            {/* Modal */}
            <div
                className="modal fade show d-block"
                role="dialog"
                aria-modal="true"
                aria-labelledby="confirm-dialog-title"
                style={{ zIndex: 1055 }}
            >
                <div className="modal-dialog modal-dialog-centered">
                    <div className="modal-content">
                        <div className="modal-header border-bottom-0 pb-0">
                            <h5 className="modal-title fw-semibold" id="confirm-dialog-title">
                                {title}
                            </h5>
                            <button
                                type="button"
                                className="btn-close"
                                onClick={onCancel}
                                aria-label="Close"
                                disabled={processing}
                            />
                        </div>
                        <div className="modal-body pt-2 text-muted">
                            {message}
                        </div>
                        <div className="modal-footer border-top-0">
                            <button
                                type="button"
                                className="btn btn-outline-secondary"
                                onClick={onCancel}
                                disabled={processing}
                            >
                                Cancel
                            </button>
                            <button
                                type="button"
                                className="btn btn-danger"
                                onClick={onConfirm}
                                disabled={processing}
                            >
                                {processing && (
                                    <span
                                        className="spinner-border spinner-border-sm me-2"
                                        role="status"
                                        aria-hidden="true"
                                    />
                                )}
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </>
    )
}
