import { useForm } from '@inertiajs/react'
import type { FormEvent } from 'react'
import FormField from '../../components/FormField'
import ResourceForm from '../../components/ResourceForm'
import AdminLayout from '../../layouts/AdminLayout'
import type { StarchFactory } from '../../types'

interface Props { item: StarchFactory }

export default function StarchFactoriesEdit({ item }: Props) {
    const { data, setData, put, processing, errors } = useForm({
        factory_name: item.factory_name, factory_label: item.factory_label,
        country: item.country, factory_active: item.factory_active, sort_order: String(item.sort_order ?? ''),
    })
    function handleSubmit(e: FormEvent) { e.preventDefault(); put(`/admin/starch-factories/${item.id}`) }
    return (
        <AdminLayout title="Edit Starch Factory">
            <div className="mx-auto" style={{ maxWidth: 640 }}>
                <ResourceForm title={`Edit: ${item.factory_name}`} onSubmit={handleSubmit} processing={processing} onCancel={() => window.history.back()}>
                    <div className="row g-3">
                        <div className="col-md-8"><FormField label="Factory Name" required error={errors.factory_name}><input type="text" className={`form-control ${errors.factory_name ? 'is-invalid' : ''}`} value={data.factory_name} onChange={e => setData('factory_name', e.target.value)} /></FormField></div>
                        <div className="col-md-4"><FormField label="Country" required error={errors.country}><input type="text" maxLength={2} className={`form-control ${errors.country ? 'is-invalid' : ''}`} value={data.country} onChange={e => setData('country', e.target.value.toUpperCase())} /></FormField></div>
                        <div className="col-md-6"><FormField label="Factory Label" required error={errors.factory_label}><input type="text" className={`form-control ${errors.factory_label ? 'is-invalid' : ''}`} value={data.factory_label} onChange={e => setData('factory_label', e.target.value)} /></FormField></div>
                        <div className="col-md-3"><FormField label="Sort Order" error={errors.sort_order}><input type="number" min={0} className="form-control" value={data.sort_order} onChange={e => setData('sort_order', e.target.value)} /></FormField></div>
                        <div className="col-md-3 d-flex align-items-end pb-3"><div className="form-check"><input type="checkbox" className="form-check-input" id="factory_active" checked={data.factory_active} onChange={e => setData('factory_active', e.target.checked)} /><label className="form-check-label" htmlFor="factory_active">Active</label></div></div>
                    </div>
                </ResourceForm>
            </div>
        </AdminLayout>
    )
}
