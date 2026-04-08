import { useForm } from '@inertiajs/react'
import type { FormEvent } from 'react'
import FormField from '../../components/FormField'
import ResourceForm from '../../components/ResourceForm'
import AdminLayout from '../../layouts/AdminLayout'
import type { Fertilizer } from '../../types'

interface Props { item: Fertilizer }

export default function FertilizersEdit({ item }: Props) {
    const { data, setData, put, processing, errors } = useForm({
        name: item.name, type: item.type, fertilizer_key: item.fertilizer_key,
        fertilizer_label: item.fertilizer_label ?? '', weight: String(item.weight),
        country: item.country, sort_order: String(item.sort_order ?? ''),
        use_case: item.use_case, cis: item.cis, cim: item.cim, available: item.available,
    })

    function handleSubmit(e: FormEvent) { e.preventDefault(); put(`/admin/fertilizers/${item.id}`) }

    return (
        <AdminLayout title="Edit Fertilizer">
            <div className="mx-auto" style={{ maxWidth: 640 }}>
                <ResourceForm title={`Edit: ${item.name}`} onSubmit={handleSubmit} processing={processing} onCancel={() => window.history.back()}>
                    <div className="row g-3">
                        <div className="col-md-6">
                            <FormField label="Name" required error={errors.name}>
                                <input type="text" className={`form-control ${errors.name ? 'is-invalid' : ''}`} value={data.name} onChange={e => setData('name', e.target.value)} />
                            </FormField>
                        </div>
                        <div className="col-md-6">
                            <FormField label="Type" required error={errors.type}>
                                <select className={`form-select ${errors.type ? 'is-invalid' : ''}`} value={data.type} onChange={e => setData('type', e.target.value)}>
                                    <option value="">Select type</option>
                                    <option value="STRAIGHT">STRAIGHT</option>
                                    <option value="COMPOUND">COMPOUND</option>
                                    <option value="ORGANIC">ORGANIC</option>
                                </select>
                            </FormField>
                        </div>
                        <div className="col-md-6">
                            <FormField label="Fertilizer Key" required error={errors.fertilizer_key}>
                                <input type="text" className={`form-control ${errors.fertilizer_key ? 'is-invalid' : ''}`} value={data.fertilizer_key} onChange={e => setData('fertilizer_key', e.target.value)} />
                            </FormField>
                        </div>
                        <div className="col-md-6">
                            <FormField label="Fertilizer Label" error={errors.fertilizer_label}>
                                <input type="text" className="form-control" value={data.fertilizer_label} onChange={e => setData('fertilizer_label', e.target.value)} />
                            </FormField>
                        </div>
                        <div className="col-md-4">
                            <FormField label="Country (2-letter)" required error={errors.country}>
                                <input type="text" maxLength={2} className={`form-control ${errors.country ? 'is-invalid' : ''}`} value={data.country} onChange={e => setData('country', e.target.value.toUpperCase())} />
                            </FormField>
                        </div>
                        <div className="col-md-4">
                            <FormField label="Weight (kg)" required error={errors.weight}>
                                <input type="number" min={1} className={`form-control ${errors.weight ? 'is-invalid' : ''}`} value={data.weight} onChange={e => setData('weight', e.target.value)} />
                            </FormField>
                        </div>
                        <div className="col-md-4">
                            <FormField label="Sort Order" error={errors.sort_order}>
                                <input type="number" min={0} className="form-control" value={data.sort_order} onChange={e => setData('sort_order', e.target.value)} />
                            </FormField>
                        </div>
                        <div className="col-md-6">
                            <FormField label="Use Case" required error={errors.use_case}>
                                <input type="text" className={`form-control ${errors.use_case ? 'is-invalid' : ''}`} value={data.use_case} onChange={e => setData('use_case', e.target.value)} />
                            </FormField>
                        </div>
                        <div className="col-12">
                            <div className="d-flex gap-4">
                                <div className="form-check"><input type="checkbox" className="form-check-input" id="available" checked={data.available} onChange={e => setData('available', e.target.checked)} /><label className="form-check-label" htmlFor="available">Available</label></div>
                                <div className="form-check"><input type="checkbox" className="form-check-input" id="cis" checked={data.cis} onChange={e => setData('cis', e.target.checked)} /><label className="form-check-label" htmlFor="cis">CIS</label></div>
                                <div className="form-check"><input type="checkbox" className="form-check-input" id="cim" checked={data.cim} onChange={e => setData('cim', e.target.checked)} /><label className="form-check-label" htmlFor="cim">CIM</label></div>
                            </div>
                        </div>
                    </div>
                </ResourceForm>
            </div>
        </AdminLayout>
    )
}
