import { useForm } from '@inertiajs/react'
import type { FormEvent } from 'react'
import FormField from '../../components/FormField'
import ResourceForm from '../../components/ResourceForm'
import AdminLayout from '../../layouts/AdminLayout'
import type { DefaultPrice } from '../../types'

interface Props { item: DefaultPrice }

export default function DefaultPricesEdit({ item }: Props) {
    const { data, setData, put, processing, errors } = useForm({
        country: item.country, item: item.item, price: String(item.price),
        unit: item.unit, currency: item.currency ?? '',
    })
    function handleSubmit(e: FormEvent) { e.preventDefault(); put(`/admin/default-prices/${item.id}`) }
    return (
        <AdminLayout title="Edit Default Price">
            <div className="mx-auto" style={{ maxWidth: 600 }}>
                <ResourceForm title={`Edit: ${item.item} (${item.country})`} onSubmit={handleSubmit} processing={processing} onCancel={() => window.history.back()}>
                    <div className="row g-3">
                        <div className="col-md-3"><FormField label="Country" required error={errors.country}><input type="text" maxLength={2} className={`form-control ${errors.country ? 'is-invalid' : ''}`} value={data.country} onChange={e => setData('country', e.target.value.toUpperCase())} /></FormField></div>
                        <div className="col-md-9"><FormField label="Item" required error={errors.item}><input type="text" className={`form-control ${errors.item ? 'is-invalid' : ''}`} value={data.item} onChange={e => setData('item', e.target.value)} /></FormField></div>
                        <div className="col-md-4"><FormField label="Price" required error={errors.price}><input type="number" min={0} step="0.01" className={`form-control ${errors.price ? 'is-invalid' : ''}`} value={data.price} onChange={e => setData('price', e.target.value)} /></FormField></div>
                        <div className="col-md-4"><FormField label="Unit" required error={errors.unit}><input type="text" className={`form-control ${errors.unit ? 'is-invalid' : ''}`} value={data.unit} onChange={e => setData('unit', e.target.value)} /></FormField></div>
                        <div className="col-md-4"><FormField label="Currency" error={errors.currency}><input type="text" maxLength={3} className="form-control" value={data.currency} onChange={e => setData('currency', e.target.value.toUpperCase())} /></FormField></div>
                    </div>
                </ResourceForm>
            </div>
        </AdminLayout>
    )
}
