import { useForm } from '@inertiajs/react'
import type { FormEvent } from 'react'
import FormField from '../../components/FormField'
import ResourceForm from '../../components/ResourceForm'
import CountrySelect from '../../components/CountrySelect'
import AdminLayout from '../../layouts/AdminLayout'
import type { InvestmentAmount } from '../../types'

interface Props { item: InvestmentAmount }

export default function InvestmentAmountsEdit({ item }: Props) {
    const { data, setData, put, processing, errors } = useForm({
        country: item.country, investment_amount: String(item.investment_amount),
        area_unit: item.area_unit, price_active: item.price_active, sort_order: String(item.sort_order ?? ''),
    })
    function handleSubmit(e: FormEvent) { e.preventDefault(); put(`/admin/investment-amounts/${item.id}`) }
    return (
        <AdminLayout title="Edit Investment Amount">
            <div className="mx-auto" style={{ maxWidth: 600 }}>
                <ResourceForm title="Edit Investment Amount" onSubmit={handleSubmit} processing={processing} onCancel={() => window.history.back()}>
                    <div className="row g-3">
                        <div className="col-md-4"><FormField label="Country" required error={errors.country}><CountrySelect value={data.country} onChange={(v) => setData('country', v)} error={errors.country} required /></FormField></div>
                        <div className="col-md-8"><FormField label="Investment Amount" required error={errors.investment_amount}><input type="number" min={0} step="0.01" className={`form-control ${errors.investment_amount ? 'is-invalid' : ''}`} value={data.investment_amount} onChange={e => setData('investment_amount', e.target.value)} /></FormField></div>
                        <div className="col-md-6"><FormField label="Area Unit" required error={errors.area_unit}><input type="text" className={`form-control ${errors.area_unit ? 'is-invalid' : ''}`} value={data.area_unit} onChange={e => setData('area_unit', e.target.value)} /></FormField></div>
                        <div className="col-md-3"><FormField label="Sort Order" error={errors.sort_order}><input type="number" min={0} className="form-control" value={data.sort_order} onChange={e => setData('sort_order', e.target.value)} /></FormField></div>
                        <div className="col-md-3 d-flex align-items-end pb-3"><div className="form-check"><input type="checkbox" className="form-check-input" id="price_active" checked={data.price_active} onChange={e => setData('price_active', e.target.checked)} /><label className="form-check-label" htmlFor="price_active">Active</label></div></div>
                    </div>
                </ResourceForm>
            </div>
        </AdminLayout>
    )
}
