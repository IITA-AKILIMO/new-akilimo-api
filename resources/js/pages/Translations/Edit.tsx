import { useForm } from '@inertiajs/react'
import type { FormEvent } from 'react'
import FormField from '../../components/FormField'
import ResourceForm from '../../components/ResourceForm'
import AdminLayout from '../../layouts/AdminLayout'
import type { Translation } from '../../types'

interface Props { item: Translation }

export default function TranslationsEdit({ item }: Props) {
    const { data, setData, put, processing, errors } = useForm({ key: item.key, en: item.en, sw: item.sw ?? '', rw: item.rw ?? '' })
    function handleSubmit(e: FormEvent) { e.preventDefault(); put(`/admin/translations/${item.id}`) }
    return (
        <AdminLayout title="Edit Translation">
            <div className="mx-auto" style={{ maxWidth: 700 }}>
                <ResourceForm title={`Edit: ${item.key}`} onSubmit={handleSubmit} processing={processing} onCancel={() => window.history.back()}>
                    <FormField label="Key" required error={errors.key}>
                        <input type="text" className={`form-control ${errors.key ? 'is-invalid' : ''}`} value={data.key} onChange={e => setData('key', e.target.value)} />
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
