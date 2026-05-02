import { router } from '@inertiajs/react'
import { useEffect, useState } from 'react'
import AdminLayout from '../../layouts/AdminLayout'
import type { Translation } from '../../types'

interface EditableRow {
    id: number | null
    key: string
    en: string
    sw: string
    rw: string
}

interface Props {
    rows: Translation[]
    search: string
    limit: number
}

const emptyRow = (): EditableRow => ({ id: null, key: '', en: '', sw: '', rw: '' })

function translationToRow(t: Translation): EditableRow {
    return {
        id: t.id,
        key: t.key,
        en: t.en ?? '',
        sw: t.sw ?? '',
        rw: t.rw ?? '',
    }
}

export default function TranslationsBatchEdit({ rows: initialRows, search: initialSearch, limit }: Props) {
    const [rows, setRows] = useState<EditableRow[]>(initialRows.length ? initialRows.map(translationToRow) : [emptyRow()])
    const [deletedIds, setDeletedIds] = useState<number[]>([])
    const [searchInput, setSearchInput] = useState(initialSearch)
    const [processing, setProcessing] = useState(false)
    const [errors, setErrors] = useState<Record<string, string>>({})

    useEffect(() => {
        setRows(initialRows.length ? initialRows.map(translationToRow) : [emptyRow()])
        setDeletedIds([])
        setErrors({})
    }, [initialSearch])

    function applySearch() {
        router.get('/admin/translations/batch-edit', { search: searchInput, limit }, { preserveState: false })
    }

    function updateRow(index: number, field: keyof EditableRow, value: string) {
        setRows((prev) => prev.map((r, i) => i === index ? { ...r, [field]: value } : r))
    }

    function addRow() { setRows((prev) => [...prev, emptyRow()]) }

    function removeRow(index: number) {
        const row = rows[index]
        if (row.id !== null) setDeletedIds((prev) => [...prev, row.id as number])
        setRows((prev) => prev.length === 1 ? [emptyRow()] : prev.filter((_, i) => i !== index))
    }

    function handleSubmit(e: React.FormEvent) {
        e.preventDefault()
        setErrors({})
        setProcessing(true)
        router.put('/admin/translations/batch', {
            rows,
            deleted_ids: deletedIds,
        }, {
            onError: (errs) => { setErrors(errs); setProcessing(false) },
            onFinish: () => setProcessing(false),
        })
    }

    return (
        <AdminLayout title="Batch Edit Translations">
            <form onSubmit={handleSubmit}>
                <div className="card border-0 shadow-sm mb-4">
                    <div className="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                        <h5 className="mb-0 fw-semibold">Batch Edit Translations</h5>
                        <span className="text-muted small">{rows.filter(r => r.id !== null).length} loaded (max {limit})</span>
                    </div>
                    <div className="card-body">
                        <div className="row mb-4">
                            <div className="col-md-5">
                                <div className="input-group input-group-sm">
                                    <input
                                        type="text"
                                        className="form-control"
                                        placeholder="Filter by key…"
                                        value={searchInput}
                                        onChange={(e) => setSearchInput(e.target.value)}
                                        onKeyDown={(e) => { if (e.key === 'Enter') applySearch() }}
                                    />
                                    <button type="button" className="btn btn-outline-secondary" onClick={applySearch}>
                                        Load
                                    </button>
                                    {searchInput && (
                                        <button type="button" className="btn btn-outline-secondary" onClick={() => { setSearchInput(''); router.get('/admin/translations/batch-edit', { limit }, { preserveState: false }) }}>
                                            Clear
                                        </button>
                                    )}
                                </div>
                                <div className="form-text">Press Enter or click Load to filter. Shows first {limit} matches.</div>
                            </div>
                        </div>

                        <div className="table-responsive mb-3">
                            <table className="table table-bordered table-sm align-middle mb-0">
                                <thead className="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th style={{ minWidth: 200 }}>Key</th>
                                        <th style={{ minWidth: 250 }}>English</th>
                                        <th style={{ minWidth: 200 }}>Swahili</th>
                                        <th style={{ minWidth: 200 }}>Kinyarwanda</th>
                                        <th style={{ width: 50 }}></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {rows.map((row, i) => (
                                        <tr key={row.id ?? `new-${i}`} className={row.id === null ? 'table-success' : ''}>
                                            <td className="text-center">
                                                <span className={`badge ${row.id === null ? 'bg-success' : 'bg-secondary'}`}>{i + 1}</span>
                                            </td>
                                            <td>
                                                <input
                                                    type="text" maxLength={255}
                                                    className={`form-control form-control-sm ${errors[`rows.${i}.key`] ? 'is-invalid' : ''}`}
                                                    value={row.key}
                                                    onChange={(e) => updateRow(i, 'key', e.target.value)}
                                                    required placeholder="translation.key"
                                                />
                                                {errors[`rows.${i}.key`] && <div className="invalid-feedback">{errors[`rows.${i}.key`]}</div>}
                                            </td>
                                            <td>
                                                <input
                                                    type="text"
                                                    className={`form-control form-control-sm ${errors[`rows.${i}.en`] ? 'is-invalid' : ''}`}
                                                    value={row.en}
                                                    onChange={(e) => updateRow(i, 'en', e.target.value)}
                                                    required placeholder="English text"
                                                />
                                                {errors[`rows.${i}.en`] && <div className="invalid-feedback">{errors[`rows.${i}.en`]}</div>}
                                            </td>
                                            <td>
                                                <input
                                                    type="text"
                                                    className="form-control form-control-sm"
                                                    value={row.sw}
                                                    onChange={(e) => updateRow(i, 'sw', e.target.value)}
                                                    placeholder="Swahili text"
                                                />
                                            </td>
                                            <td>
                                                <input
                                                    type="text"
                                                    className="form-control form-control-sm"
                                                    value={row.rw}
                                                    onChange={(e) => updateRow(i, 'rw', e.target.value)}
                                                    placeholder="Kinyarwanda text"
                                                />
                                            </td>
                                            <td className="text-center">
                                                <button
                                                    type="button"
                                                    className="btn btn-outline-danger btn-sm py-0 px-2"
                                                    onClick={() => removeRow(i)}
                                                    title={row.id !== null ? 'Delete this record' : 'Remove row'}
                                                >✕</button>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>

                        <div className="d-flex align-items-center gap-3">
                            <button type="button" className="btn btn-outline-secondary btn-sm" onClick={addRow}>
                                + Add Row
                            </button>
                            {deletedIds.length > 0 && (
                                <span className="text-danger small">
                                    {deletedIds.length} record{deletedIds.length !== 1 ? 's' : ''} marked for deletion
                                </span>
                            )}
                        </div>
                        {errors.rows && <div className="text-danger small mt-2">{errors.rows}</div>}
                    </div>

                    <div className="card-footer bg-white border-top d-flex gap-2 justify-content-end">
                        <button type="button" className="btn btn-outline-secondary" onClick={() => window.history.back()}>Cancel</button>
                        <button type="submit" className="btn btn-success" disabled={processing}>
                            {processing
                                ? <><span className="spinner-border spinner-border-sm me-2" />Saving…</>
                                : 'Save Changes'}
                        </button>
                    </div>
                </div>
            </form>
        </AdminLayout>
    )
}
