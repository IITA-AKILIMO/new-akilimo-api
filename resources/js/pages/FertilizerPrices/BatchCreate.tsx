import { router } from '@inertiajs/react'
import { useState } from 'react'
import CountrySelect from '../../components/CountrySelect'
import FormField from '../../components/FormField'
import AdminLayout from '../../layouts/AdminLayout'

interface PriceRow {
    fertilizer_key: string
    min_price: string
    max_price: string
    price_per_bag: string
    price_active: boolean
    sort_order: string
    desc: string
}

const emptyRow = (): PriceRow => ({
    fertilizer_key: '',
    min_price: '',
    max_price: '',
    price_per_bag: '',
    price_active: true,
    sort_order: '',
    desc: '',
})

export default function FertilizerPricesBatchCreate() {
    const [country, setCountry] = useState('')
    const [rows, setRows] = useState<PriceRow[]>([emptyRow()])
    const [processing, setProcessing] = useState(false)
    const [errors, setErrors] = useState<Record<string, string>>({})

    function updateRow(index: number, field: keyof PriceRow, value: string | boolean) {
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
        router.post('/admin/fertilizer-prices/batch', { country, rows }, {
            onError: (errs) => { setErrors(errs); setProcessing(false) },
            onFinish: () => setProcessing(false),
        })
    }

    return (
        <AdminLayout title="Batch Add Fertilizer Prices">
            <form onSubmit={handleSubmit}>
                <div className="card border-0 shadow-sm mb-4">
                    <div className="card-header bg-white border-bottom py-3">
                        <h5 className="mb-0 fw-semibold">Batch Create Fertilizer Prices</h5>
                    </div>
                    <div className="card-body">
                        <div className="row mb-4">
                            <div className="col-md-4">
                                <FormField label="Country" required error={errors.country}>
                                    <CountrySelect
                                        value={country}
                                        onChange={setCountry}
                                        error={errors.country}
                                        required
                                    />
                                </FormField>
                            </div>
                        </div>

                        <div className="table-responsive mb-3">
                            <table className="table table-bordered table-sm align-middle mb-0">
                                <thead className="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Fertilizer Key</th>
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
                                    {rows.map((row, i) => (
                                        <tr key={i}>
                                            <td className="text-muted text-center">{i + 1}</td>
                                            <td>
                                                <input
                                                    type="text" maxLength={50}
                                                    className={`form-control form-control-sm ${errors[`rows.${i}.fertilizer_key`] ? 'is-invalid' : ''}`}
                                                    value={row.fertilizer_key}
                                                    onChange={(e) => updateRow(i, 'fertilizer_key', e.target.value)}
                                                    required placeholder="e.g. urea"
                                                />
                                                {errors[`rows.${i}.fertilizer_key`] && <div className="invalid-feedback">{errors[`rows.${i}.fertilizer_key`]}</div>}
                                            </td>
                                            <td>
                                                <input
                                                    type="number" min={0} step="0.01"
                                                    className={`form-control form-control-sm ${errors[`rows.${i}.min_price`] ? 'is-invalid' : ''}`}
                                                    value={row.min_price}
                                                    onChange={(e) => updateRow(i, 'min_price', e.target.value)}
                                                    required placeholder="0.00"
                                                />
                                            </td>
                                            <td>
                                                <input
                                                    type="number" min={0} step="0.01"
                                                    className={`form-control form-control-sm ${errors[`rows.${i}.max_price`] ? 'is-invalid' : ''}`}
                                                    value={row.max_price}
                                                    onChange={(e) => updateRow(i, 'max_price', e.target.value)}
                                                    required placeholder="0.00"
                                                />
                                            </td>
                                            <td>
                                                <input
                                                    type="number" min={0} step="0.01"
                                                    className={`form-control form-control-sm ${errors[`rows.${i}.price_per_bag`] ? 'is-invalid' : ''}`}
                                                    value={row.price_per_bag}
                                                    onChange={(e) => updateRow(i, 'price_per_bag', e.target.value)}
                                                    required placeholder="0.00"
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
                                                    placeholder="0"
                                                />
                                            </td>
                                            <td>
                                                <input
                                                    type="text" maxLength={255}
                                                    className="form-control form-control-sm"
                                                    value={row.desc}
                                                    onChange={(e) => updateRow(i, 'desc', e.target.value)}
                                                    placeholder="Optional"
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
