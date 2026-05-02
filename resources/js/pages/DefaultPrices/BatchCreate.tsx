import { router } from '@inertiajs/react'
import { useState } from 'react'
import CountrySelect from '../../components/CountrySelect'
import FormField from '../../components/FormField'
import AdminLayout from '../../layouts/AdminLayout'

interface PriceRow {
    item: string
    price: string
    unit: string
    currency: string
}

const emptyRow = (): PriceRow => ({ item: '', price: '', unit: '', currency: '' })

export default function DefaultPricesBatchCreate() {
    const [country, setCountry] = useState('')
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
        router.post('/admin/default-prices/batch', { country, rows }, {
            onError: (errs) => { setErrors(errs); setProcessing(false) },
            onFinish: () => setProcessing(false),
        })
    }

    return (
        <AdminLayout title="Batch Add Default Prices">
            <form onSubmit={handleSubmit}>
                <div className="card border-0 shadow-sm mb-4">
                    <div className="card-header bg-white border-bottom py-3">
                        <h5 className="mb-0 fw-semibold">Batch Create Default Prices</h5>
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
                                        <th>Item</th>
                                        <th>Price</th>
                                        <th>Unit</th>
                                        <th style={{ width: 110 }}>Currency</th>
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
                                                    className={`form-control form-control-sm ${errors[`rows.${i}.item`] ? 'is-invalid' : ''}`}
                                                    value={row.item}
                                                    onChange={(e) => updateRow(i, 'item', e.target.value)}
                                                    required placeholder="e.g. Cassava"
                                                />
                                                {errors[`rows.${i}.item`] && <div className="invalid-feedback">{errors[`rows.${i}.item`]}</div>}
                                            </td>
                                            <td>
                                                <input
                                                    type="number" min={0} step="0.01"
                                                    className={`form-control form-control-sm ${errors[`rows.${i}.price`] ? 'is-invalid' : ''}`}
                                                    value={row.price}
                                                    onChange={(e) => updateRow(i, 'price', e.target.value)}
                                                    required placeholder="0.00"
                                                />
                                            </td>
                                            <td>
                                                <input
                                                    type="text" maxLength={15}
                                                    className={`form-control form-control-sm ${errors[`rows.${i}.unit`] ? 'is-invalid' : ''}`}
                                                    value={row.unit}
                                                    onChange={(e) => updateRow(i, 'unit', e.target.value)}
                                                    required placeholder="e.g. kg"
                                                />
                                            </td>
                                            <td>
                                                <input
                                                    type="text" maxLength={3}
                                                    className="form-control form-control-sm"
                                                    value={row.currency}
                                                    onChange={(e) => updateRow(i, 'currency', e.target.value)}
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
