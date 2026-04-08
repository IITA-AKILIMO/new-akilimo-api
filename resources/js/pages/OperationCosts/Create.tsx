import { useForm } from '@inertiajs/react'
import type { FormEvent } from 'react'
import FormField from '../../components/FormField'
import ResourceForm from '../../components/ResourceForm'
import AdminLayout from '../../layouts/AdminLayout'

export default function OperationCostsCreate() {
    const { data, setData, post, processing, errors } = useForm({
        operation_name: '', operation_type: '', country_code: '', min_cost: '', max_cost: '', is_active: true,
    })
    function handleSubmit(e: FormEvent) { e.preventDefault(); post('/admin/operation-costs') }
    return (
        <AdminLayout title="New Operation Cost">
            <div className="mx-auto" style={{ maxWidth: 640 }}>
                <ResourceForm title="Create Operation Cost" onSubmit={handleSubmit} processing={processing} onCancel={() => window.history.back()}>
                    <div className="row g-3">
                        <div className="col-md-8"><FormField label="Operation Name" required error={errors.operation_name}><input type="text" className={`form-control ${errors.operation_name ? 'is-invalid' : ''}`} value={data.operation_name} onChange={e => setData('operation_name', e.target.value)} /></FormField></div>
                        <div className="col-md-4"><FormField label="Country Code" required error={errors.country_code}><input type="text" maxLength={2} className={`form-control ${errors.country_code ? 'is-invalid' : ''}`} value={data.country_code} onChange={e => setData('country_code', e.target.value.toUpperCase())} /></FormField></div>
                        <div className="col-md-6"><FormField label="Operation Type" required error={errors.operation_type}><input type="text" className={`form-control ${errors.operation_type ? 'is-invalid' : ''}`} value={data.operation_type} onChange={e => setData('operation_type', e.target.value)} /></FormField></div>
                        <div className="col-md-3"><FormField label="Min Cost" required error={errors.min_cost}><input type="number" min={0} step="0.01" className={`form-control ${errors.min_cost ? 'is-invalid' : ''}`} value={data.min_cost} onChange={e => setData('min_cost', e.target.value)} /></FormField></div>
                        <div className="col-md-3"><FormField label="Max Cost" required error={errors.max_cost}><input type="number" min={0} step="0.01" className={`form-control ${errors.max_cost ? 'is-invalid' : ''}`} value={data.max_cost} onChange={e => setData('max_cost', e.target.value)} /></FormField></div>
                        <div className="col-12"><div className="form-check"><input type="checkbox" className="form-check-input" id="is_active" checked={data.is_active} onChange={e => setData('is_active', e.target.checked)} /><label className="form-check-label" htmlFor="is_active">Active</label></div></div>
                    </div>
                </ResourceForm>
            </div>
        </AdminLayout>
    )
}
