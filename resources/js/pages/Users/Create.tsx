import { useForm } from '@inertiajs/react'
import type { FormEvent } from 'react'
import FormField from '../../components/FormField'
import ResourceForm from '../../components/ResourceForm'
import AdminLayout from '../../layouts/AdminLayout'
import type { UserRole } from '../../types'

interface FormData {
    name: string
    username: string
    email: string
    role: UserRole
    password: string
    password_confirmation: string
}

const ROLE_OPTIONS: { value: UserRole; label: string; description: string }[] = [
    { value: 'user',       label: 'User',       description: 'Can compute recommendations only' },
    { value: 'partner',    label: 'Partner',    description: 'Can compute, read history, submit prices & feedback' },
    { value: 'admin',      label: 'Admin',      description: 'Full access to all resources' },
]

export default function UsersCreate() {
    const { data, setData, post, processing, errors } = useForm<FormData>({
        name: '',
        username: '',
        email: '',
        role: 'user',
        password: '',
        password_confirmation: '',
    })

    function handleSubmit(e: FormEvent) {
        e.preventDefault()
        post('/admin/users')
    }

    return (
        <AdminLayout title="New User">
            <div className="mx-auto" style={{ maxWidth: 640 }}>
                <ResourceForm
                    title="Create User"
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
                            placeholder="Full name"
                        />
                    </FormField>

                    <FormField label="Username" required error={errors.username}>
                        <input
                            type="text"
                            value={data.username}
                            onChange={(e) => setData('username', e.target.value)}
                            className={`form-control ${errors.username ? 'is-invalid' : ''}`}
                            placeholder="username"
                            autoComplete="username"
                        />
                    </FormField>

                    <FormField label="Email" required error={errors.email}>
                        <input
                            type="email"
                            value={data.email}
                            onChange={(e) => setData('email', e.target.value)}
                            className={`form-control ${errors.email ? 'is-invalid' : ''}`}
                            placeholder="user@example.com"
                            autoComplete="email"
                        />
                    </FormField>

                    <FormField label="Role" required error={errors.role}>
                        <select
                            value={data.role}
                            onChange={(e) => setData('role', e.target.value as UserRole)}
                            className={`form-select ${errors.role ? 'is-invalid' : ''}`}
                        >
                            {ROLE_OPTIONS.map((r) => (
                                <option key={r.value} value={r.value}>{r.label}</option>
                            ))}
                        </select>
                        <div className="form-text">
                            {ROLE_OPTIONS.find((r) => r.value === data.role)?.description}
                        </div>
                    </FormField>

                    <FormField label="Password" required error={errors.password}>
                        <input
                            type="password"
                            value={data.password}
                            onChange={(e) => setData('password', e.target.value)}
                            className={`form-control ${errors.password ? 'is-invalid' : ''}`}
                            autoComplete="new-password"
                        />
                    </FormField>

                    <FormField label="Confirm Password" required error={errors.password_confirmation}>
                        <input
                            type="password"
                            value={data.password_confirmation}
                            onChange={(e) => setData('password_confirmation', e.target.value)}
                            className={`form-control ${errors.password_confirmation ? 'is-invalid' : ''}`}
                            autoComplete="new-password"
                        />
                    </FormField>
                </ResourceForm>
            </div>
        </AdminLayout>
    )
}
