import { useForm } from '@inertiajs/react'
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

    function handleSubmit(e: React.FormEvent) {
        e.preventDefault()
        put(`/admin/users/${user.id}`)
    }

    return (
        <AdminLayout title="Edit User">
            <div className="mx-auto max-w-2xl">
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
                            className="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 outline-none transition focus:border-green-500 focus:ring-2 focus:ring-green-500/20"
                        />
                    </FormField>

                    <FormField label="Username" required error={errors.username}>
                        <input
                            type="text"
                            value={data.username}
                            onChange={(e) => setData('username', e.target.value)}
                            className="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 outline-none transition focus:border-green-500 focus:ring-2 focus:ring-green-500/20"
                            autoComplete="username"
                        />
                    </FormField>

                    <FormField label="Email" required error={errors.email}>
                        <input
                            type="email"
                            value={data.email}
                            onChange={(e) => setData('email', e.target.value)}
                            className="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 outline-none transition focus:border-green-500 focus:ring-2 focus:ring-green-500/20"
                            autoComplete="email"
                        />
                    </FormField>

                    <div className="rounded-lg border border-gray-100 bg-gray-50 p-4">
                        <p className="mb-4 text-xs font-medium uppercase tracking-wide text-gray-400">
                            Change Password — leave blank to keep current
                        </p>
                        <div className="space-y-4">
                            <FormField label="New Password" error={errors.password}>
                                <input
                                    type="password"
                                    value={data.password}
                                    onChange={(e) => setData('password', e.target.value)}
                                    className="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 outline-none transition focus:border-green-500 focus:ring-2 focus:ring-green-500/20"
                                    autoComplete="new-password"
                                />
                            </FormField>

                            <FormField label="Confirm New Password" error={errors.password_confirmation}>
                                <input
                                    type="password"
                                    value={data.password_confirmation}
                                    onChange={(e) => setData('password_confirmation', e.target.value)}
                                    className="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 outline-none transition focus:border-green-500 focus:ring-2 focus:ring-green-500/20"
                                    autoComplete="new-password"
                                />
                            </FormField>
                        </div>
                    </div>
                </ResourceForm>
            </div>
        </AdminLayout>
    )
}
