import { useForm } from '@inertiajs/react'
import type { FormEvent } from 'react'
import FormField from '../../components/FormField'
import ResourceForm from '../../components/ResourceForm'
import AdminLayout from '../../layouts/AdminLayout'

export default function CountriesCreate() {
    const { data, setData, post, processing, errors } = useForm({
        code: '',
        name: '',
        active: true,
        sort_order: '',
    })

    function handleSubmit(e: FormEvent) {
        e.preventDefault()
        post('/admin/countries')
    }

    return (
        <AdminLayout title="New Country">
            <div className="mx-auto" style={{ maxWidth: 480 }}>
                <ResourceForm
                    title="Add Country"
                    onSubmit={handleSubmit}
                    processing={processing}
                    onCancel={() => window.history.back()}
                >
                    <div className="row g-3">
                        <div className="col-md-3">
                            <FormField label="Code" required error={errors.code}>
                                <input
                                    type="text"
                                    maxLength={2}
                                    className={`form-control text-uppercase ${errors.code ? 'is-invalid' : ''}`}
                                    value={data.code}
                                    onChange={(e) => setData('code', e.target.value.toUpperCase())}
                                    placeholder="NG"
                                />
                            </FormField>
                        </div>
                        <div className="col-md-9">
                            <FormField label="Country Name" required error={errors.name}>
                                <input
                                    type="text"
                                    maxLength={100}
                                    className={`form-control ${errors.name ? 'is-invalid' : ''}`}
                                    value={data.name}
                                    onChange={(e) => setData('name', e.target.value)}
                                    placeholder="Nigeria"
                                />
                            </FormField>
                        </div>
                        <div className="col-md-4">
                            <FormField label="Sort Order" error={errors.sort_order}>
                                <input
                                    type="number"
                                    min={0}
                                    step={1}
                                    className={`form-control ${errors.sort_order ? 'is-invalid' : ''}`}
                                    value={data.sort_order}
                                    onChange={(e) => setData('sort_order', e.target.value)}
                                    placeholder="0"
                                />
                            </FormField>
                        </div>
                        <div className="col-md-8 d-flex align-items-end pb-1">
                            <div className="form-check">
                                <input
                                    type="checkbox"
                                    className="form-check-input"
                                    id="active"
                                    checked={data.active}
                                    onChange={(e) => setData('active', e.target.checked)}
                                />
                                <label className="form-check-label" htmlFor="active">Active (visible in dropdowns)</label>
                            </div>
                        </div>
                    </div>
                </ResourceForm>
            </div>
        </AdminLayout>
    )
}
