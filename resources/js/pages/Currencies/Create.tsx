import { useForm } from '@inertiajs/react'
import type { FormEvent } from 'react'
import FormField from '../../components/FormField'
import ResourceForm from '../../components/ResourceForm'
import AdminLayout from '../../layouts/AdminLayout'

export default function CurrenciesCreate() {
    const { data, setData, post, processing, errors } = useForm({
        country_code: '', country: '', currency_name: '', currency_code: '',
        currency_symbol: '', currency_native_symbol: '', name_plural: '',
    })
    function handleSubmit(e: FormEvent) { e.preventDefault(); post('/admin/currencies') }
    return (
        <AdminLayout title="New Currency">
            <div className="mx-auto" style={{ maxWidth: 640 }}>
                <ResourceForm title="Create Currency" onSubmit={handleSubmit} processing={processing} onCancel={() => window.history.back()}>
                    <div className="row g-3">
                        <div className="col-md-3"><FormField label="Country Code" required error={errors.country_code}><input type="text" maxLength={2} className={`form-control ${errors.country_code ? 'is-invalid' : ''}`} value={data.country_code} onChange={e => setData('country_code', e.target.value.toUpperCase())} /></FormField></div>
                        <div className="col-md-9"><FormField label="Country" required error={errors.country}><input type="text" className={`form-control ${errors.country ? 'is-invalid' : ''}`} value={data.country} onChange={e => setData('country', e.target.value)} /></FormField></div>
                        <div className="col-md-6"><FormField label="Currency Name" required error={errors.currency_name}><input type="text" className={`form-control ${errors.currency_name ? 'is-invalid' : ''}`} value={data.currency_name} onChange={e => setData('currency_name', e.target.value)} /></FormField></div>
                        <div className="col-md-3"><FormField label="ISO Code" required error={errors.currency_code}><input type="text" maxLength={10} className={`form-control ${errors.currency_code ? 'is-invalid' : ''}`} value={data.currency_code} onChange={e => setData('currency_code', e.target.value.toUpperCase())} /></FormField></div>
                        <div className="col-md-3"><FormField label="Symbol" required error={errors.currency_symbol}><input type="text" maxLength={10} className={`form-control ${errors.currency_symbol ? 'is-invalid' : ''}`} value={data.currency_symbol} onChange={e => setData('currency_symbol', e.target.value)} /></FormField></div>
                        <div className="col-md-4"><FormField label="Native Symbol" error={errors.currency_native_symbol}><input type="text" maxLength={10} className="form-control" value={data.currency_native_symbol} onChange={e => setData('currency_native_symbol', e.target.value)} /></FormField></div>
                        <div className="col-md-8"><FormField label="Name Plural" error={errors.name_plural}><input type="text" className="form-control" value={data.name_plural} onChange={e => setData('name_plural', e.target.value)} /></FormField></div>
                    </div>
                </ResourceForm>
            </div>
        </AdminLayout>
    )
}
