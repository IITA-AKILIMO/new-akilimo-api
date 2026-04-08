import { useForm } from '@inertiajs/react'
import type { FormEvent } from 'react'
import FormField from '../../components/FormField'
import ResourceForm from '../../components/ResourceForm'
import AdminLayout from '../../layouts/AdminLayout'
import type { User } from '../../types'

interface Props {
    user: User
}

interface FormData {
    name: string
    username: string
    email: string
    password: string
    password_confirmation: string
}

export default function UsersEdit({ user }: Props) {
    const { data, setData, put, processing, errors } = useForm<FormData>({
        name: user.name,
        username: user.username,
        email: user.email,
        password: '',
        password_confirmation: '',
    })

    function handleSubmit(e: FormEvent) {
        e.preventDefault()
        put(`/admin/users/${user.id}`)
    }

    return (
        <AdminLayout title="Edit User">
            <div className="mx-auto" style={{ maxWidth: 640 }}>
                <ResourceForm
                    title={`Edit: ${user.name}`}
                    onSubmit={handleSubmit}
                    processing={processing}
                    onCancel={() => window.history.back()}
                >
                    <FormField label="Name" required error={errors.name}>
                        <input
                            type="text"
                            value={data.name}
                            onChange={(e) => setData('name', e.target.value)}
                            className={`form-control ${errors.name ? 'is-invalid' : ''}`}
                        />
                    </FormField>

                    <FormField label="Username" required error={errors.username}>
                        <input
                            type="text"
                            value={data.username}
                            onChange={(e) => setData('username', e.target.value)}
                            className={`form-control ${errors.username ? 'is-invalid' : ''}`}
                            autoComplete="username"
                        />
                    </FormField>

                    <FormField label="Email" required error={errors.email}>
                        <input
                            type="email"
                            value={data.email}
                            onChange={(e) => setData('email', e.target.value)}
                            className={`form-control ${errors.email ? 'is-invalid' : ''}`}
                            autoComplete="email"
                        />
                    </FormField>

                    <div className="p-3 bg-light rounded border mb-3">
                        <p className="text-muted small text-uppercase fw-semibold mb-3" style={{ letterSpacing: '0.05em' }}>
                            Change Password — leave blank to keep current
                        </p>
                        <FormField label="New Password" error={errors.password}>
                            <input
                                type="password"
                                value={data.password}
                                onChange={(e) => setData('password', e.target.value)}
                                className={`form-control ${errors.password ? 'is-invalid' : ''}`}
                                autoComplete="new-password"
                            />
                        </FormField>

                        <FormField label="Confirm New Password" error={errors.password_confirmation}>
                            <input
                                type="password"
                                value={data.password_confirmation}
                                onChange={(e) => setData('password_confirmation', e.target.value)}
                                className={`form-control ${errors.password_confirmation ? 'is-invalid' : ''}`}
                                autoComplete="new-password"
                            />
                        </FormField>
                    </div>
                </ResourceForm>
            </div>
        </AdminLayout>
    )
}
