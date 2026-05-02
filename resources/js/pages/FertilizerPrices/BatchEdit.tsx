import { router } from '@inertiajs/react'
import { useEffect, useMemo, useState } from 'react'
import CountrySelect from '../../components/CountrySelect'
import FormField from '../../components/FormField'
import AdminLayout from '../../layouts/AdminLayout'
import type { FertilizerPrice } from '../../types'

interface EditableRow {
    id: number | null
    fertilizer_key: string
    min_price: string
    max_price: string
    price_per_bag: string
    price_active: boolean
    sort_order: string
    desc: string
}

interface Props {
    prices: (FertilizerPrice & { desc: string })[]
    country: string | null
    /** fertilizer_key → type mapping from the fertilizers table */
    fertilizerTypes: Record<string, string>
}

const emptyRow = (): EditableRow => ({
    id: null,
    fertilizer_key: '',
    min_price: '',
    max_price: '',
    price_per_bag: '',
    price_active: true,
    sort_order: '',
    desc: '',
})

function priceToRow(p: Props['prices'][number]): EditableRow {
    return {
        id: p.id,
        fertilizer_key: p.fertilizer_key,
        min_price: String(p.min_price),
        max_price: String(p.max_price),
        price_per_bag: String(p.price_per_bag),
        price_active: p.price_active,
        sort_order: p.sort_order != null ? String(p.sort_order) : '',
        desc: p.desc ?? '',
    }
}

export default function FertilizerPricesBatchEdit({ prices, country, fertilizerTypes }: Props) {
    const [countryCode, setCountryCode] = useState(country ?? '')
    const [rows, setRows] = useState<EditableRow[]>(prices.length ? prices.map(priceToRow) : [emptyRow()])
    const [deletedIds, setDeletedIds] = useState<number[]>([])
    const [typeFilter, setTypeFilter] = useState('')
    const [processing, setProcessing] = useState(false)
    const [errors, setErrors] = useState<Record<string, string>>({})

    useEffect(() => {
        setRows(prices.length ? prices.map(priceToRow) : [emptyRow()])
        setDeletedIds([])
        setTypeFilter('')
        setErrors({})
    }, [country])

    // Distinct types present in the loaded fertilizers for this country
    const availableTypes = useMemo(
        () => [...new Set(Object.values(fertilizerTypes))].sort(),
        [fertilizerTypes],
    )

    // Index of rows matching the current type filter (by global row index)
    const visibleIndices = useMemo(() => {
        if (!typeFilter) return rows.map((_, i) => i)
        return rows.reduce<number[]>((acc, row, i) => {
            const rowType = fertilizerTypes[row.fertilizer_key] ?? ''
            // New rows (no key yet) always show when a filter is active so users can fill them in
            if (!row.fertilizer_key || rowType === typeFilter) acc.push(i)
            return acc
        }, [])
    }, [rows, typeFilter, fertilizerTypes])

    function handleCountryChange(code: string) {
        setCountryCode(code)
        if (code) router.get('/admin/fertilizer-prices/batch-edit', { country: code }, { preserveState: false })
    }

    function updateRow(index: number, field: keyof EditableRow, value: string | boolean) {
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
        router.put('/admin/fertilizer-prices/batch', {
            country: countryCode,
            rows,
            deleted_ids: deletedIds,
        }, {
            onError: (errs) => { setErrors(errs); setProcessing(false) },
            onFinish: () => setProcessing(false),
        })
    }

    const hasCountry = !!countryCode
    const hiddenCount = rows.length - visibleIndices.length

    return (
        <AdminLayout title="Batch Edit Fertilizer Prices">
            <form onSubmit={handleSubmit}>
                <div className="card border-0 shadow-sm mb-4">
                    <div className="card-header bg-white border-bottom py-3">
                        <h5 className="mb-0 fw-semibold">Batch Edit Fertilizer Prices</h5>
                    </div>
                    <div className="card-body">
                        <div className="row mb-4 g-3">
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
                            {hasCountry && availableTypes.length > 0 && (
                                <div className="col-md-4">
                                    <FormField label="Filter by Fertilizer Type">
                                        <select
                                            className="form-select form-select-sm"
                                            value={typeFilter}
                                            onChange={(e) => setTypeFilter(e.target.value)}
                                        >
                                            <option value="">All types ({rows.length})</option>
                                            {availableTypes.map((t) => {
                                                const count = rows.filter((r) => (fertilizerTypes[r.fertilizer_key] ?? '') === t).length
                                                return <option key={t} value={t}>{t} ({count})</option>
                                            })}
                                        </select>
                                    </FormField>
                                </div>
                            )}
                            {hasCountry && prices.length > 0 && (
                                <div className="col-md-4 d-flex align-items-end">
                                    <span className="text-muted small">
                                        {prices.length} record{prices.length !== 1 ? 's' : ''} for <strong>{countryCode}</strong>
                                        {typeFilter && hiddenCount > 0 && <> &mdash; {hiddenCount} hidden by filter</>}
                                    </span>
                                </div>
                            )}
                        </div>

                        {!hasCountry && (
                            <div className="text-center text-muted py-4 border rounded">
                                Select a country above to load its prices for editing.
                            </div>
                        )}

                        {hasCountry && (
                            <>
                                <div className="table-responsive mb-3">
                                    <table className="table table-bordered table-sm align-middle mb-0">
                                        <thead className="table-light">
                                            <tr>
                                                <th style={{ width: 50 }}>#</th>
                                                <th>Fertilizer Key</th>
                                                {availableTypes.length > 0 && <th style={{ width: 120 }}>Type</th>}
                                                <th>Min Price</th>
                                                <th>Max Price</th>
                                                <th>Price/Bag</th>
                                                <th style={{ width: 80 }}>Active</th>
                                                <th style={{ width: 90 }}>Sort Order</th>
                                                <th>Description</th>
                                                <th style={{ width: 50 }}></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {visibleIndices.map((i) => {
                                                const row = rows[i]
                                                const rowType = fertilizerTypes[row.fertilizer_key] ?? ''
                                                return (
                                                    <tr key={row.id ?? `new-${i}`} className={row.id === null ? 'table-success' : ''}>
                                                        <td className="text-center">
                                                            <span className={`badge ${row.id === null ? 'bg-success' : 'bg-secondary'}`}>{i + 1}</span>
                                                        </td>
                                                        <td>
                                                            <input
                                                                type="text" maxLength={50}
                                                                className={`form-control form-control-sm ${errors[`rows.${i}.fertilizer_key`] ? 'is-invalid' : ''}`}
                                                                value={row.fertilizer_key}
                                                                onChange={(e) => updateRow(i, 'fertilizer_key', e.target.value)}
                                                                required
                                                            />
                                                            {errors[`rows.${i}.fertilizer_key`] && <div className="invalid-feedback">{errors[`rows.${i}.fertilizer_key`]}</div>}
                                                        </td>
                                                        {availableTypes.length > 0 && (
                                                            <td>
                                                                {rowType
                                                                    ? <span className="badge bg-light text-dark border">{rowType}</span>
                                                                    : <span className="text-muted small">—</span>}
                                                            </td>
                                                        )}
                                                        <td>
                                                            <input
                                                                type="number" min={0} step="0.01"
                                                                className={`form-control form-control-sm ${errors[`rows.${i}.min_price`] ? 'is-invalid' : ''}`}
                                                                value={row.min_price}
                                                                onChange={(e) => updateRow(i, 'min_price', e.target.value)}
                                                                required
                                                            />
                                                        </td>
                                                        <td>
                                                            <input
                                                                type="number" min={0} step="0.01"
                                                                className={`form-control form-control-sm ${errors[`rows.${i}.max_price`] ? 'is-invalid' : ''}`}
                                                                value={row.max_price}
                                                                onChange={(e) => updateRow(i, 'max_price', e.target.value)}
                                                                required
                                                            />
                                                        </td>
                                                        <td>
                                                            <input
                                                                type="number" min={0} step="0.01"
                                                                className={`form-control form-control-sm ${errors[`rows.${i}.price_per_bag`] ? 'is-invalid' : ''}`}
                                                                value={row.price_per_bag}
                                                                onChange={(e) => updateRow(i, 'price_per_bag', e.target.value)}
                                                                required
                                                            />
                                                        </td>
                                                        <td className="text-center">
                                                            <input
                                                                type="checkbox"
                                                                className="form-check-input"
                                                                checked={row.price_active}
                                                                onChange={(e) => updateRow(i, 'price_active', e.target.checked)}
                                                            />
                                                        </td>
                                                        <td>
                                                            <input
                                                                type="number" min={0} step="1"
                                                                className="form-control form-control-sm"
                                                                value={row.sort_order}
                                                                onChange={(e) => updateRow(i, 'sort_order', e.target.value)}
                                                            />
                                                        </td>
                                                        <td>
                                                            <input
                                                                type="text" maxLength={255}
                                                                className="form-control form-control-sm"
                                                                value={row.desc}
                                                                onChange={(e) => updateRow(i, 'desc', e.target.value)}
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
                                                )
                                            })}
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
