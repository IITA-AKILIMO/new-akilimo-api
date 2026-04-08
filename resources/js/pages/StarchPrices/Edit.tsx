import { useForm } from '@inertiajs/react'
import type { FormEvent } from 'react'
import FormField from '../../components/FormField'
import ResourceForm from '../../components/ResourceForm'
import AdminLayout from '../../layouts/AdminLayout'
import type { StarchFactory, StarchPrice } from '../../types'

interface Props { item: StarchPrice; factories: StarchFactory[] }

export default function StarchPricesEdit({ item, factories }: Props) {
    const { data, setData, put, processing, errors } = useForm({
        starch_factory_id: String(item.starch_factory_id), price_class: String(item.price_class),
        min_starch: String(item.min_starch), range_starch: item.range_starch ?? '',
        price: String(item.price), currency: item.currency ?? '',
    })
    function handleSubmit(e: FormEvent) { e.preventDefault(); put(`/admin/starch-prices/${item.id}`) }
    return (
        <AdminLayout title="Edit Starch Price">
            <div className="mx-auto" style={{ maxWidth: 640 }}>
                <ResourceForm title="Edit Starch Price" onSubmit={handleSubmit} processing={processing} onCancel={() => window.history.back()}>
                    <div className="row g-3">
                        <div className="col-12"><FormField label="Starch Factory" required error={errors.starch_factory_id}>
                            <select className={`form-select ${errors.starch_factory_id ? 'is-invalid' : ''}`} value={data.starch_factory_id} onChange={e => setData('starch_factory_id', e.target.value)}>
                                <option value="">Select factory…</option>
                                {factories.map(f => <option key={f.id} value={f.id}>{f.factory_name} ({f.country})</option>)}
                            </select>
                        </FormField></div>
                        <div className="col-md-4"><FormField label="Price Class" required error={errors.price_class}><input type="number" min={0} className={`form-control ${errors.price_class ? 'is-invalid' : ''}`} value={data.price_class} onChange={e => setData('price_class', e.target.value)} /></FormField></div>
                        <div className="col-md-4"><FormField label="Min Starch %" required error={errors.min_starch}><input type="number" min={0} step="0.01" className={`form-control ${errors.min_starch ? 'is-invalid' : ''}`} value={data.min_starch} onChange={e => setData('min_starch', e.target.value)} /></FormField></div>
                        <div className="col-md-4"><FormField label="Range Starch" error={errors.range_starch}><input type="text" className="form-control" value={data.range_starch} onChange={e => setData('range_starch', e.target.value)} /></FormField></div>
                        <div className="col-md-6"><FormField label="Price" required error={errors.price}><input type="number" min={0} step="0.01" className={`form-control ${errors.price ? 'is-invalid' : ''}`} value={data.price} onChange={e => setData('price', e.target.value)} /></FormField></div>
                        <div className="col-md-6"><FormField label="Currency" error={errors.currency}><input type="text" maxLength={10} className="form-control" value={data.currency} onChange={e => setData('currency', e.target.value)} /></FormField></div>
                    </div>
                </ResourceForm>
            </div>
        </AdminLayout>
    )
}
