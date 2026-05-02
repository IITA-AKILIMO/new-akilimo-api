import { router } from '@inertiajs/react'
import { useEffect, useState } from 'react'
import CountrySelect from '../../components/CountrySelect'
import FormField from '../../components/FormField'
import AdminLayout from '../../layouts/AdminLayout'
import type { DefaultPrice } from '../../types'

interface EditableRow {
    id: number | null
    item: string
    price: string
    unit: string
    currency: string
}

interface Props {
    prices: DefaultPrice[]
    country: string | null
}

const emptyRow = (): EditableRow => ({ id: null, item: '', price: '', unit: '', currency: '' })

function priceToRow(p: DefaultPrice): EditableRow {
    return {
        id: p.id,
        item: p.item,
        price: String(p.price),
        unit: p.unit,
        currency: p.currency ?? '',
    }
}

export default function DefaultPricesBatchEdit({ prices, country }: Props) {
    const [countryCode, setCountryCode] = useState(country ?? '')
    const [rows, setRows] = useState<EditableRow[]>(prices.length ? prices.map(priceToRow) : [emptyRow()])
    const [deletedIds, setDeletedIds] = useState<number[]>([])
    const [processing, setProcessing] = useState(false)
    const [errors, setErrors] = useState<Record<string, string>>({})

    useEffect(() => {
        setRows(prices.length ? prices.map(priceToRow) : [emptyRow()])
        setDeletedIds([])
        setErrors({})
    }, [country])

    function handleCountryChange(code: string) {
        setCountryCode(code)
        if (code) router.get('/admin/default-prices/batch-edit', { country: code }, { preserveState: false })
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
        router.put('/admin/default-prices/batch', {
            country: countryCode,
            rows,
            deleted_ids: deletedIds,
        }, {
            onError: (errs) => { setErrors(errs); setProcessing(false) },
            onFinish: () => setProcessing(false),
        })
    }

    const hasCountry = !!countryCode

    return (
        <AdminLayout title="Batch Edit Default Prices">
            <form onSubmit={handleSubmit}>
                <div className="card border-0 shadow-sm mb-4">
                    <div className="card-header bg-white border-bottom py-3">
                        <h5 className="mb-0 fw-semibold">Batch Edit Default Prices</h5>
                    </div>
                    <div className="card-body">
                        <div className="row mb-4">
                            <div className="col-md-4">
                                <FormField label="Country" required error={errors.country}>
                                    <CountrySelect
                                        value={countryCode}
                                        onChange={handleCountryChange}
                                        error={errors.country}
                                        required
                                    />
                                </FormField>
                            </div>
                            {hasCountry && prices.length > 0 && (
                                <div className="col-md-8 d-flex align-items-end">
                                    <span className="text-muted small">
                                        {prices.length} existing record{prices.length !== 1 ? 's' : ''} for <strong>{countryCode}</strong>
                                    </span>
                                </div>
                            )}
                        </div>

                        {!hasCountry && (
                            <div className="text-center text-muted py-4 border rounded">
                                Select a country above to load its default prices for editing.
                            </div>
                        )}

                        {hasCountry && (
                            <>
                                <div className="table-responsive mb-3">
                                    <table className="table table-bordered table-sm align-middle mb-0">
                                        <thead className="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Item</th>
                                                <th>Price</th>
                                                <th>Unit</th>
                                                <th style={{ width: 110 }}>Currency</th>
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
                                                            type="text" maxLength={50}
                                                            className={`form-control form-control-sm ${errors[`rows.${i}.item`] ? 'is-invalid' : ''}`}
                                                            value={row.item}
                                                            onChange={(e) => updateRow(i, 'item', e.target.value)}
                                                            required
                                                        />
                                                        {errors[`rows.${i}.item`] && <div className="invalid-feedback">{errors[`rows.${i}.item`]}</div>}
                                                    </td>
                                                    <td>
                                                        <input
                                                            type="number" min={0} step="0.01"
                                                            className={`form-control form-control-sm ${errors[`rows.${i}.price`] ? 'is-invalid' : ''}`}
                                                            value={row.price}
                                                            onChange={(e) => updateRow(i, 'price', e.target.value)}
                                                            required
                                                        />
                                                    </td>
                                                    <td>
                                                        <input
                                                            type="text" maxLength={15}
                                                            className={`form-control form-control-sm ${errors[`rows.${i}.unit`] ? 'is-invalid' : ''}`}
                                                            value={row.unit}
                                                            onChange={(e) => updateRow(i, 'unit', e.target.value)}
                                                            required
                                                        />
                                                    </td>
                                                    <td>
                                                        <input
                                                            type="text" maxLength={3}
                                                            className="form-control form-control-sm"
                                                            value={row.currency}
                                                            onChange={(e) => updateRow(i, 'currency', e.target.value)}
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
                        <button type="submit" className="btn btn-success" disabled={processing || !hasCountry}>
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
