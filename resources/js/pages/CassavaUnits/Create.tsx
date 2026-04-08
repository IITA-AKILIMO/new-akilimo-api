import { useForm } from '@inertiajs/react'
import type { FormEvent } from 'react'
import FormField from '../../components/FormField'
import ResourceForm from '../../components/ResourceForm'
import AdminLayout from '../../layouts/AdminLayout'

export default function CassavaUnitsCreate() {
    const { data, setData, post, processing, errors } = useForm({
        label: '', unit_weight: '', sort_order: '', description: '', is_active: true,
    })
    function handleSubmit(e: FormEvent) { e.preventDefault(); post('/admin/cassava-units') }
    return (
        <AdminLayout title="New Cassava Unit">
            <div className="mx-auto" style={{ maxWidth: 600 }}>
                <ResourceForm title="Create Cassava Unit" onSubmit={handleSubmit} processing={processing} onCancel={() => window.history.back()}>
                    <div className="row g-3">
                        <div className="col-md-6"><FormField label="Label" required error={errors.label}><input type="text" className={`form-control ${errors.label ? 'is-invalid' : ''}`} value={data.label} onChange={e => setData('label', e.target.value)} /></FormField></div>
                        <div className="col-md-3"><FormField label="Weight (kg)" required error={errors.unit_weight}><input type="number" min={0} step="0.01" className={`form-control ${errors.unit_weight ? 'is-invalid' : ''}`} value={data.unit_weight} onChange={e => setData('unit_weight', e.target.value)} /></FormField></div>
                        <div className="col-md-3"><FormField label="Sort Order" error={errors.sort_order}><input type="number" min={0} className="form-control" value={data.sort_order} onChange={e => setData('sort_order', e.target.value)} /></FormField></div>
                        <div className="col-12"><FormField label="Description" error={errors.description}><input type="text" className="form-control" value={data.description} onChange={e => setData('description', e.target.value)} /></FormField></div>
                        <div className="col-12"><div className="form-check"><input type="checkbox" className="form-check-input" id="is_active" checked={data.is_active} onChange={e => setData('is_active', e.target.checked)} /><label className="form-check-label" htmlFor="is_active">Active</label></div></div>
                    </div>
                </ResourceForm>
            </div>
        </AdminLayout>
    )
}
