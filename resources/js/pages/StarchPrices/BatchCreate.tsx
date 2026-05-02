import { router } from '@inertiajs/react'
import { useState } from 'react'
import FormField from '../../components/FormField'
import AdminLayout from '../../layouts/AdminLayout'
import type { StarchFactory } from '../../types'

interface PriceRow {
    min_starch: string
    range_starch: string
    price: string
    currency: string
}

interface Props {
    factories: Pick<StarchFactory, 'id' | 'factory_name' | 'country'>[]
}

const emptyRow = (): PriceRow => ({ min_starch: '', range_starch: '', price: '', currency: '' })

export default function StarchPricesBatchCreate({ factories }: Props) {
    const [factoryId, setFactoryId] = useState('')
    const [rows, setRows] = useState<PriceRow[]>([emptyRow()])
    const [processing, setProcessing] = useState(false)
    const [errors, setErrors] = useState<Record<string, string>>({})

    function updateRow(index: number, field: keyof PriceRow, value: string) {
        setRows((prev) => prev.map((r, i) => i === index ? { ...r, [field]: value } : r))
    }

    function addRow() { setRows((prev) => [...prev, emptyRow()]) }

    function removeRow(index: number) {
        if (rows.length === 1) return
        setRows((prev) => prev.filter((_, i) => i !== index))
    }

    function handleSubmit(e: React.FormEvent) {
        e.preventDefault()
        setErrors({})
        setProcessing(true)
        // Inject price_class as 1-based position at submit time
        const payload = rows.map((row, i) => ({ ...row, price_class: i + 1 }))
        router.post('/admin/starch-prices/batch', { factory_id: factoryId, rows: payload }, {
            onError: (errs) => { setErrors(errs); setProcessing(false) },
            onFinish: () => setProcessing(false),
        })
    }

    const selectedFactory = factories.find((f) => String(f.id) === factoryId)

    return (
        <AdminLayout title="Batch Add Starch Prices">
            <form onSubmit={handleSubmit}>
                <div className="card border-0 shadow-sm mb-4">
                    <div className="card-header bg-white border-bottom py-3">
                        <h5 className="mb-0 fw-semibold">Batch Create Starch Prices</h5>
                    </div>
                    <div className="card-body">

                        <div className="row mb-4">
                            <div className="col-md-6">
                                <FormField label="Starch Factory" required error={errors.factory_id}>
                                    <select
                                        className={`form-select ${errors.factory_id ? 'is-invalid' : ''}`}
                                        value={factoryId}
                                        onChange={(e) => setFactoryId(e.target.value)}
                                        required
                                    >
                                        <option value="">Select factory…</option>
                                        {factories.map((f) => (
                                            <option key={f.id} value={f.id}>{f.factory_name} ({f.country})</option>
                                        ))}
                                    </select>
                                </FormField>
                            </div>
                            {selectedFactory && (
                                <div className="col-md-6 d-flex align-items-end">
                                    <span className="text-muted small">
                                        Adding prices for <strong>{selectedFactory.factory_name}</strong>
                                    </span>
                                </div>
                            )}
                        </div>

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
                                        <tr key={i}>
                                            <td className="text-center">
                                                <span className="badge bg-secondary fs-6 px-3">{i + 1}</span>
                                            </td>
                                            <td>
                                                <input
                                                    type="number" min={0} step="0.01"
                                                    className={`form-control form-control-sm ${errors[`rows.${i}.min_starch`] ? 'is-invalid' : ''}`}
                                                    value={row.min_starch} onChange={(e) => updateRow(i, 'min_starch', e.target.value)}
                                                    required placeholder="e.g. 20.00"
                                                />
                                                {errors[`rows.${i}.min_starch`] && <div className="invalid-feedback">{errors[`rows.${i}.min_starch`]}</div>}
                                            </td>
                                            <td>
                                                <input
                                                    type="text" className="form-control form-control-sm"
                                                    value={row.range_starch} onChange={(e) => updateRow(i, 'range_starch', e.target.value)}
                                                    placeholder="e.g. 20–25"
                                                />
                                            </td>
                                            <td>
                                                <input
                                                    type="number" min={0} step="0.01"
                                                    className={`form-control form-control-sm ${errors[`rows.${i}.price`] ? 'is-invalid' : ''}`}
                                                    value={row.price} onChange={(e) => updateRow(i, 'price', e.target.value)}
                                                    required placeholder="0.00"
                                                />
                                                {errors[`rows.${i}.price`] && <div className="invalid-feedback">{errors[`rows.${i}.price`]}</div>}
                                            </td>
                                            <td>
                                                <input
                                                    type="text" maxLength={10} className="form-control form-control-sm"
                                                    value={row.currency} onChange={(e) => updateRow(i, 'currency', e.target.value)}
                                                    placeholder="USD"
                                                />
                                            </td>
                                            <td className="text-center">
                                                <button
                                                    type="button"
                                                    className="btn btn-outline-danger btn-sm py-0 px-2"
                                                    onClick={() => removeRow(i)}
                                                    disabled={rows.length === 1}
                                                    title="Remove row"
                                                >✕</button>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>

                        <button type="button" className="btn btn-outline-secondary btn-sm" onClick={addRow}>
                            + Add Row
                        </button>
                        {errors.rows && <div className="text-danger small mt-2">{errors.rows}</div>}
                    </div>

                    <div className="card-footer bg-white border-top d-flex gap-2 justify-content-end">
                        <button type="button" className="btn btn-outline-secondary" onClick={() => window.history.back()}>Cancel</button>
                        <button type="submit" className="btn btn-success" disabled={processing}>
                            {processing
                                ? <><span className="spinner-border spinner-border-sm me-2" />Saving…</>
                                : `Save ${rows.length} Price${rows.length !== 1 ? 's' : ''}`}
                        </button>
                    </div>
                </div>
            </form>
        </AdminLayout>
    )
}
