import { useForm } from '@inertiajs/react'
import type { FormEvent } from 'react'
import FormField from '../../components/FormField'
import ResourceForm from '../../components/ResourceForm'
import AdminLayout from '../../layouts/AdminLayout'
import type { MaizePrice } from '../../types'

interface Props { item: MaizePrice }

export default function MaizePricesEdit({ item }: Props) {
    const { data, setData, put, processing, errors } = useForm({
        country: item.country, produce_type: item.produce_type,
        min_local_price: String(item.min_local_price), max_local_price: String(item.max_local_price),
        min_usd: String(item.min_usd), max_usd: String(item.max_usd),
        min_price: String(item.min_price), max_price: String(item.max_price),
        price_active: item.price_active, sort_order: String(item.sort_order ?? ''),
    })
    function handleSubmit(e: FormEvent) { e.preventDefault(); put(`/admin/maize-prices/${item.id}`) }
    return (
        <AdminLayout title="Edit Maize Price">
            <div className="mx-auto" style={{ maxWidth: 700 }}>
                <ResourceForm title="Edit Maize Price" onSubmit={handleSubmit} processing={processing} onCancel={() => window.history.back()}>
                    <div className="row g-3">
                        <div className="col-md-4"><FormField label="Country" required error={errors.country}><input type="text" maxLength={2} className={`form-control ${errors.country ? 'is-invalid' : ''}`} value={data.country} onChange={e => setData('country', e.target.value.toUpperCase())} /></FormField></div>
                        <div className="col-md-8"><FormField label="Produce Type" required error={errors.produce_type}><input type="text" className={`form-control ${errors.produce_type ? 'is-invalid' : ''}`} value={data.produce_type} onChange={e => setData('produce_type', e.target.value)} /></FormField></div>
                        <div className="col-md-3"><FormField label="Min Local Price" required error={errors.min_local_price}><input type="number" min={0} step="0.01" className={`form-control ${errors.min_local_price ? 'is-invalid' : ''}`} value={data.min_local_price} onChange={e => setData('min_local_price', e.target.value)} /></FormField></div>
                        <div className="col-md-3"><FormField label="Max Local Price" required error={errors.max_local_price}><input type="number" min={0} step="0.01" className={`form-control ${errors.max_local_price ? 'is-invalid' : ''}`} value={data.max_local_price} onChange={e => setData('max_local_price', e.target.value)} /></FormField></div>
                        <div className="col-md-3"><FormField label="Min USD" required error={errors.min_usd}><input type="number" min={0} step="0.01" className={`form-control ${errors.min_usd ? 'is-invalid' : ''}`} value={data.min_usd} onChange={e => setData('min_usd', e.target.value)} /></FormField></div>
                        <div className="col-md-3"><FormField label="Max USD" required error={errors.max_usd}><input type="number" min={0} step="0.01" className={`form-control ${errors.max_usd ? 'is-invalid' : ''}`} value={data.max_usd} onChange={e => setData('max_usd', e.target.value)} /></FormField></div>
                        <div className="col-md-3"><FormField label="Min Price" required error={errors.min_price}><input type="number" min={0} step="0.01" className={`form-control ${errors.min_price ? 'is-invalid' : ''}`} value={data.min_price} onChange={e => setData('min_price', e.target.value)} /></FormField></div>
                        <div className="col-md-3"><FormField label="Max Price" required error={errors.max_price}><input type="number" min={0} step="0.01" className={`form-control ${errors.max_price ? 'is-invalid' : ''}`} value={data.max_price} onChange={e => setData('max_price', e.target.value)} /></FormField></div>
                        <div className="col-md-3"><FormField label="Sort Order" error={errors.sort_order}><input type="number" min={0} className="form-control" value={data.sort_order} onChange={e => setData('sort_order', e.target.value)} /></FormField></div>
                        <div className="col-md-3 d-flex align-items-end pb-3"><div className="form-check"><input type="checkbox" className="form-check-input" id="price_active" checked={data.price_active} onChange={e => setData('price_active', e.target.checked)} /><label className="form-check-label" htmlFor="price_active">Active</label></div></div>
                    </div>
                </ResourceForm>
            </div>
        </AdminLayout>
    )
}
