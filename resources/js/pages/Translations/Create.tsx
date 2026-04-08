import { useForm } from '@inertiajs/react'
import type { FormEvent } from 'react'
import FormField from '../../components/FormField'
import ResourceForm from '../../components/ResourceForm'
import AdminLayout from '../../layouts/AdminLayout'

export default function TranslationsCreate() {
    const { data, setData, post, processing, errors } = useForm({ key: '', en: '', sw: '', rw: '' })
    function handleSubmit(e: FormEvent) { e.preventDefault(); post('/admin/translations') }
    return (
        <AdminLayout title="New Translation">
            <div className="mx-auto" style={{ maxWidth: 700 }}>
                <ResourceForm title="Create Translation" onSubmit={handleSubmit} processing={processing} onCancel={() => window.history.back()}>
                    <FormField label="Key" required error={errors.key}>
                        <input type="text" className={`form-control ${errors.key ? 'is-invalid' : ''}`} value={data.key} onChange={e => setData('key', e.target.value)} placeholder="e.g. label.recommendation_title" />
                    </FormField>
                    <FormField label="English" required error={errors.en}>
                        <textarea rows={3} className={`form-control ${errors.en ? 'is-invalid' : ''}`} value={data.en} onChange={e => setData('en', e.target.value)} />
                    </FormField>
                    <FormField label="Swahili (sw)" error={errors.sw}>
                        <textarea rows={3} className="form-control" value={data.sw} onChange={e => setData('sw', e.target.value)} />
                    </FormField>
                    <FormField label="Kinyarwanda (rw)" error={errors.rw}>
                        <textarea rows={3} className="form-control" value={data.rw} onChange={e => setData('rw', e.target.value)} />
                    </FormField>
                </ResourceForm>
            </div>
        </AdminLayout>
    )
}
