import { useForm } from '@inertiajs/react'
import type { FormEvent } from 'react'
import FormField from '../../components/FormField'
import ResourceForm from '../../components/ResourceForm'
import CountrySelect from '../../components/CountrySelect'
import AdminLayout from '../../layouts/AdminLayout'
import type { Currency } from '../../types'

interface Props { item: Currency }

export default function CurrenciesEdit({ item }: Props) {
    const { data, setData, put, processing, errors } = useForm({
        country_code: item.country_code, country: item.country,
        currency_name: item.currency_name, currency_code: item.currency_code,
        currency_symbol: item.currency_symbol,
        currency_native_symbol: item.currency_native_symbol ?? '',
        name_plural: item.name_plural ?? '',
    })
    function handleSubmit(e: FormEvent) { e.preventDefault(); put(`/admin/currencies/${item.id}`) }
    return (
        <AdminLayout title="Edit Currency">
            <div className="mx-auto" style={{ maxWidth: 640 }}>
                <ResourceForm title={`Edit: ${item.currency_name}`} onSubmit={handleSubmit} processing={processing} onCancel={() => window.history.back()}>
                    <div className="row g-3">
                        <div className="col-md-3"><FormField label="Country Code" required error={errors.country_code}><CountrySelect value={data.country_code} onChange={(v) => setData('country_code', v)} onNameChange={(name) => setData('country', name)} error={errors.country_code} required /></FormField></div>
                        <div className="col-md-9"><FormField label="Country Name" required error={errors.country}><input type="text" className={`form-control ${errors.country ? 'is-invalid' : ''}`} value={data.country} onChange={e => setData('country', e.target.value)} /></FormField></div>
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
