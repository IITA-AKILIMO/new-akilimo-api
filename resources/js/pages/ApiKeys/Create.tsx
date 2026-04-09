import { useForm } from '@inertiajs/react'
import type { FormEvent } from 'react'
import FormField from '../../components/FormField'
import ResourceForm from '../../components/ResourceForm'
import AdminLayout from '../../layouts/AdminLayout'

interface User {
    id: number
    name: string
    email: string
}

interface Props {
    users: User[]
    abilities: string[]
}

interface FormData {
    user_id: number | ''
    name: string
    abilities: string[]
    expires_at: string
}

export default function ApiKeysCreate({ users, abilities }: Props) {
    const { data, setData, post, processing, errors } = useForm<FormData>({
        user_id: '',
        name: '',
        abilities: [],
        expires_at: '',
    })

    function handleSubmit(e: FormEvent) {
        e.preventDefault()
        post('/admin/api-keys')
    }

    function toggleAbility(ability: string) {
        const current = data.abilities
        if (current.includes(ability)) {
            setData('abilities', current.filter((a) => a !== ability))
        } else {
            setData('abilities', [...current, ability])
        }
    }

    return (
        <AdminLayout title="New API Key">
            <div className="mx-auto" style={{ maxWidth: 640 }}>
                <ResourceForm
                    title="Create API Key"
                    onSubmit={handleSubmit}
                    processing={processing}
                    onCancel={() => window.history.back()}
                >
                    <FormField label="User" required error={errors.user_id}>
                        <select
                            value={data.user_id}
                            onChange={(e) => setData('user_id', e.target.value ? parseInt(e.target.value) : '')}
                            className={`form-select ${errors.user_id ? 'is-invalid' : ''}`}
                        >
                            <option value="">Select a user…</option>
                            {users.map((user) => (
                                <option key={user.id} value={user.id}>
                                    {user.name} ({user.email})
                                </option>
                            ))}
                        </select>
                    </FormField>

                    <FormField label="Key Name" required error={errors.name}>
                        <input
                            type="text"
                            value={data.name}
                            onChange={(e) => setData('name', e.target.value)}
                            className={`form-control ${errors.name ? 'is-invalid' : ''}`}
                            placeholder="e.g., Production API"
                        />
                        <div className="form-text">A descriptive name to identify this key</div>
                    </FormField>

                    <FormField label="Abilities" error={errors.abilities}>
                        <div className="row row-cols-2 row-cols-md-4 g-2">
                            {abilities.map((ability) => (
                                <div key={ability} className="col">
                                    <div className="form-check">
                                        <input
                                            type="checkbox"
                                            className="form-check-input"
                                            id={`ability-${ability}`}
                                            checked={data.abilities.includes(ability)}
                                            onChange={() => toggleAbility(ability)}
                                        />
                                        <label className="form-check-label" htmlFor={`ability-${ability}`}>
                                            {ability}
                                        </label>
                                    </div>
                                </div>
                            ))}
                        </div>
                        <div className="form-text">
                            Leave empty for wildcard access (*). Select specific abilities to restrict access.
                        </div>
                    </FormField>

                    <FormField label="Expiry Date" error={errors.expires_at}>
                        <input
                            type="date"
                            value={data.expires_at}
                            onChange={(e) => setData('expires_at', e.target.value)}
                            className={`form-control ${errors.expires_at ? 'is-invalid' : ''}`}
                        />
                        <div className="form-text">Optional. Leave empty for no expiration.</div>
                    </FormField>
                </ResourceForm>
            </div>
        </AdminLayout>
    )
}