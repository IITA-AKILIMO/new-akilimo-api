import { useForm } from '@inertiajs/react'
import type { FormEvent } from 'react'
import FormField from '../../components/FormField'
import ResourceForm from '../../components/ResourceForm'
import CountrySelect from '../../components/CountrySelect'
import AdminLayout from '../../layouts/AdminLayout'

export default function FertilizerPricesCreate() {
    const { data, setData, post, processing, errors } = useForm({
        country: '', fertilizer_key: '', min_price: '', max_price: '',
        price_per_bag: '', price_active: true, sort_order: '', desc: '',
    })

    function handleSubmit(e: FormEvent) { e.preventDefault(); post('/admin/fertilizer-prices') }

    return (
        <AdminLayout title="New Fertilizer Price">
            <div className="mx-auto" style={{ maxWidth: 640 }}>
                <ResourceForm title="Create Fertilizer Price" onSubmit={handleSubmit} processing={processing} onCancel={() => window.history.back()}>
                    <div className="row g-3">
                        <div className="col-md-4">
                            <FormField label="Country" required error={errors.country}>
                                <CountrySelect value={data.country} onChange={(v) => setData('country', v)} error={errors.country} required />
                            </FormField>
                        </div>
                        <div className="col-md-8">
                            <FormField label="Fertilizer Key" required error={errors.fertilizer_key}>
                                <input type="text" className={`form-control ${errors.fertilizer_key ? 'is-invalid' : ''}`} value={data.fertilizer_key} onChange={e => setData('fertilizer_key', e.target.value)} />
                            </FormField>
                        </div>
                        <div className="col-md-4">
                            <FormField label="Min Price" required error={errors.min_price}>
                                <input type="number" min={0} step="0.01" className={`form-control ${errors.min_price ? 'is-invalid' : ''}`} value={data.min_price} onChange={e => setData('min_price', e.target.value)} />
                            </FormField>
                        </div>
                        <div className="col-md-4">
                            <FormField label="Max Price" required error={errors.max_price}>
                                <input type="number" min={0} step="0.01" className={`form-control ${errors.max_price ? 'is-invalid' : ''}`} value={data.max_price} onChange={e => setData('max_price', e.target.value)} />
                            </FormField>
                        </div>
                        <div className="col-md-4">
                            <FormField label="Price per Bag" required error={errors.price_per_bag}>
                                <input type="number" min={0} step="0.01" className={`form-control ${errors.price_per_bag ? 'is-invalid' : ''}`} value={data.price_per_bag} onChange={e => setData('price_per_bag', e.target.value)} />
                            </FormField>
                        </div>
                        <div className="col-md-6">
                            <FormField label="Sort Order" error={errors.sort_order}>
                                <input type="number" min={0} className="form-control" value={data.sort_order} onChange={e => setData('sort_order', e.target.value)} />
                            </FormField>
                        </div>
                        <div className="col-md-6">
                            <FormField label="Description" error={errors.desc}>
                                <input type="text" className="form-control" value={data.desc} onChange={e => setData('desc', e.target.value)} />
                            </FormField>
                        </div>
                        <div className="col-12">
                            <div className="form-check">
                                <input type="checkbox" className="form-check-input" id="price_active" checked={data.price_active} onChange={e => setData('price_active', e.target.checked)} />
                                <label className="form-check-label" htmlFor="price_active">Active</label>
                            </div>
                        </div>
                    </div>
                </ResourceForm>
            </div>
        </AdminLayout>
    )
}
