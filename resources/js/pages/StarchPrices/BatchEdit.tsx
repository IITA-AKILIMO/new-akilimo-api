import { router } from '@inertiajs/react'
import { useEffect, useState } from 'react'
import FormField from '../../components/FormField'
import AdminLayout from '../../layouts/AdminLayout'
import type { StarchFactory, StarchPrice } from '../../types'

interface EditableRow {
    id: number | null
    min_starch: string
    range_starch: string
    price: string
    currency: string
}

interface Props {
    factories: Pick<StarchFactory, 'id' | 'factory_name' | 'country'>[]
    prices: (StarchPrice & { range_starch: string; currency: string })[]
    factory_id: number | null
}

const emptyRow = (): EditableRow => ({ id: null, min_starch: '', range_starch: '', price: '', currency: '' })

function priceToRow(p: Props['prices'][number]): EditableRow {
    return {
        id: p.id,
        min_starch: String(p.min_starch),
        range_starch: p.range_starch ?? '',
        price: String(p.price),
        currency: p.currency ?? '',
    }
}

export default function StarchPricesBatchEdit({ factories, prices, factory_id }: Props) {
    const [factoryId, setFactoryId] = useState(factory_id ? String(factory_id) : '')
    const [rows, setRows] = useState<EditableRow[]>(prices.length ? prices.map(priceToRow) : [emptyRow()])
    const [deletedIds, setDeletedIds] = useState<number[]>([])
    const [processing, setProcessing] = useState(false)
    const [errors, setErrors] = useState<Record<string, string>>({})

    useEffect(() => {
        setRows(prices.length ? prices.map(priceToRow) : [emptyRow()])
        setDeletedIds([])
        setErrors({})
    }, [factory_id])

    function handleFactoryChange(id: string) {
        setFactoryId(id)
        if (id) router.get('/admin/starch-prices/batch-edit', { factory_id: id }, { preserveState: false })
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
        // Inject price_class as 1-based position at submit time
        const payload = rows.map((row, i) => ({ ...row, price_class: i + 1 }))
        router.put('/admin/starch-prices/batch', {
            factory_id: factoryId,
            rows: payload,
            deleted_ids: deletedIds,
        }, {
            onError: (errs) => { setErrors(errs); setProcessing(false) },
            onFinish: () => setProcessing(false),
        })
    }

    const selectedFactory = factories.find((f) => String(f.id) === factoryId)
    const hasFactory = !!factoryId

    return (
        <AdminLayout title="Batch Edit Starch Prices">
            <form onSubmit={handleSubmit}>
                <div className="card border-0 shadow-sm mb-4">
                    <div className="card-header bg-white border-bottom py-3">
                        <h5 className="mb-0 fw-semibold">Batch Edit Starch Prices</h5>
                    </div>
                    <div className="card-body">

                        <div className="row mb-4">
                            <div className="col-md-6">
                                <FormField label="Starch Factory" required error={errors.factory_id}>
                                    <select
                                        className={`form-select ${errors.factory_id ? 'is-invalid' : ''}`}
                                        value={factoryId}
                                        onChange={(e) => handleFactoryChange(e.target.value)}
                                    >
                                        <option value="">Select factory to edit…</option>
                                        {factories.map((f) => (
                                            <option key={f.id} value={f.id}>{f.factory_name} ({f.country})</option>
                                        ))}
                                    </select>
                                </FormField>
                            </div>
                            {selectedFactory && (
                                <div className="col-md-6 d-flex align-items-end">
                                    <span className="text-muted small">
                                        Editing prices for <strong>{selectedFactory.factory_name}</strong>
                                        {prices.length > 0 && <> — {prices.length} existing record{prices.length !== 1 ? 's' : ''}</>}
                                    </span>
                                </div>
                            )}
                        </div>

                        {!hasFactory && (
                            <div className="text-center text-muted py-4 border rounded">
                                Select a factory above to load its prices for editing.
                            </div>
                        )}

                        {hasFactory && (
                            <>
                                <div className="table-responsive mb-3">
                                    <table className="table table-bordered table-sm align-middle mb-0">
                                        <thead className="table-light">
                                            <tr>
                                                <th style={{ width: 80 }} className="text-center">Price Class</th>
                                                <th>Min Starch %</th>
                                                <th>Range Starch</th>
                                                <th>Price</th>
                                                <th style={{ width: 110 }}>Currency</th>
                                                <th style={{ width: 50 }}></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {rows.map((row, i) => (
                                                <tr key={row.id ?? `new-${i}`} className={row.id === null ? 'table-success' : ''}>
                                                    <td className="text-center">
                                                        <span className={`badge fs-6 px-3 ${row.id === null ? 'bg-success' : 'bg-secondary'}`}>
                                                            {i + 1}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <input
                                                            type="number" min={0} step="0.01"
                                                            className={`form-control form-control-sm ${errors[`rows.${i}.min_starch`] ? 'is-invalid' : ''}`}
                                                            value={row.min_starch} onChange={(e) => updateRow(i, 'min_starch', e.target.value)}
                                                            required
                                                        />
                                                        {errors[`rows.${i}.min_starch`] && <div className="invalid-feedback">{errors[`rows.${i}.min_starch`]}</div>}
                                                    </td>
                                                    <td>
                                                        <input
                                                            type="text" className="form-control form-control-sm"
                                                            value={row.range_starch} onChange={(e) => updateRow(i, 'range_starch', e.target.value)}
                                                        />
                                                    </td>
                                                    <td>
                                                        <input
                                                            type="number" min={0} step="0.01"
                                                            className={`form-control form-control-sm ${errors[`rows.${i}.price`] ? 'is-invalid' : ''}`}
                                                            value={row.price} onChange={(e) => updateRow(i, 'price', e.target.value)}
                                                            required
                                                        />
                                                        {errors[`rows.${i}.price`] && <div className="invalid-feedback">{errors[`rows.${i}.price`]}</div>}
                                                    </td>
                                                    <td>
                                                        <input
                                                            type="text" maxLength={10} className="form-control form-control-sm"
                                                            value={row.currency} onChange={(e) => updateRow(i, 'currency', e.target.value)}
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
                            </>
                        )}
                    </div>

                    <div className="card-footer bg-white border-top d-flex gap-2 justify-content-end">
                        <button type="button" className="btn btn-outline-secondary" onClick={() => window.history.back()}>Cancel</button>
                        <button type="submit" className="btn btn-success" disabled={processing || !hasFactory}>
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
