import { router } from '@inertiajs/react'
import { useState } from 'react'
import CountrySelect from '../../components/CountrySelect'
import FormField from '../../components/FormField'
import AdminLayout from '../../layouts/AdminLayout'

interface CostRow {
    operation_name: string
    operation_type: string
    min_cost: string
    max_cost: string
    is_active: boolean
}

const emptyRow = (): CostRow => ({
    operation_name: '',
    operation_type: '',
    min_cost: '',
    max_cost: '',
    is_active: true,
})

export default function OperationCostsBatchCreate() {
    const [country, setCountry] = useState('')
    const [rows, setRows] = useState<CostRow[]>([emptyRow()])
    const [processing, setProcessing] = useState(false)
    const [errors, setErrors] = useState<Record<string, string>>({})

    function updateRow(index: number, field: keyof CostRow, value: string | boolean) {
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
        router.post('/admin/operation-costs/batch', { country, rows }, {
            onError: (errs) => { setErrors(errs); setProcessing(false) },
            onFinish: () => setProcessing(false),
        })
    }

    return (
        <AdminLayout title="Batch Add Operation Costs">
            <form onSubmit={handleSubmit}>
                <div className="card border-0 shadow-sm mb-4">
                    <div className="card-header bg-white border-bottom py-3">
                        <h5 className="mb-0 fw-semibold">Batch Create Operation Costs</h5>
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
                                        <th>Operation Name</th>
                                        <th>Operation Type</th>
                                        <th>Min Cost</th>
                                        <th>Max Cost</th>
                                        <th style={{ width: 80 }}>Active</th>
                                        <th style={{ width: 50 }}></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {rows.map((row, i) => (
                                        <tr key={i}>
                                            <td className="text-muted text-center">{i + 1}</td>
                                            <td>
                                                <input
                                                    type="text" maxLength={100}
                                                    className={`form-control form-control-sm ${errors[`rows.${i}.operation_name`] ? 'is-invalid' : ''}`}
                                                    value={row.operation_name}
                                                    onChange={(e) => updateRow(i, 'operation_name', e.target.value)}
                                                    required placeholder="e.g. Land Clearing"
                                                />
                                                {errors[`rows.${i}.operation_name`] && <div className="invalid-feedback">{errors[`rows.${i}.operation_name`]}</div>}
                                            </td>
                                            <td>
                                                <input
                                                    type="text" maxLength={50}
                                                    className={`form-control form-control-sm ${errors[`rows.${i}.operation_type`] ? 'is-invalid' : ''}`}
                                                    value={row.operation_type}
                                                    onChange={(e) => updateRow(i, 'operation_type', e.target.value)}
                                                    required placeholder="e.g. MANUAL"
                                                />
                                            </td>
                                            <td>
                                                <input
                                                    type="number" min={0} step="0.01"
                                                    className={`form-control form-control-sm ${errors[`rows.${i}.min_cost`] ? 'is-invalid' : ''}`}
                                                    value={row.min_cost}
                                                    onChange={(e) => updateRow(i, 'min_cost', e.target.value)}
                                                    required placeholder="0.00"
                                                />
                                            </td>
                                            <td>
                                                <input
                                                    type="number" min={0} step="0.01"
                                                    className={`form-control form-control-sm ${errors[`rows.${i}.max_cost`] ? 'is-invalid' : ''}`}
                                                    value={row.max_cost}
                                                    onChange={(e) => updateRow(i, 'max_cost', e.target.value)}
                                                    required placeholder="0.00"
                                                />
                                            </td>
                                            <td className="text-center">
                                                <input
                                                    type="checkbox"
                                                    className="form-check-input"
                                                    checked={row.is_active}
                                                    onChange={(e) => updateRow(i, 'is_active', e.target.checked)}
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
                                : `Save ${rows.length} Record${rows.length !== 1 ? 's' : ''}`}
                        </button>
                    </div>
                </div>
            </form>
        </AdminLayout>
    )
}
