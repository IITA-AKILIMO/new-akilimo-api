import { useForm } from '@inertiajs/react'
import FormField from '../../components/FormField'
import ResourceForm from '../../components/ResourceForm'
import AdminLayout from '../../layouts/AdminLayout'

interface FormData {
    name: string
    username: string
    email: string
    password: string
    password_confirmation: string
}

export default function UsersCreate() {
    const { data, setData, post, processing, errors } = useForm<FormData>({
        name: '',
        username: '',
        email: '',
        password: '',
        password_confirmation: '',
    })

    function handleSubmit(e: React.FormEvent) {
        e.preventDefault()
        post('/admin/users')
    }

    return (
        <AdminLayout title="New User">
            <div className="mx-auto max-w-2xl">
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
                            className="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 outline-none transition focus:border-green-500 focus:ring-2 focus:ring-green-500/20"
                            placeholder="Full name"
                        />
                    </FormField>

                    <FormField label="Username" required error={errors.username}>
                        <input
                            type="text"
                            value={data.username}
                            onChange={(e) => setData('username', e.target.value)}
                            className="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 outline-none transition focus:border-green-500 focus:ring-2 focus:ring-green-500/20"
                            placeholder="username"
                            autoComplete="username"
                        />
                    </FormField>

                    <FormField label="Email" required error={errors.email}>
                        <input
                            type="email"
                            value={data.email}
                            onChange={(e) => setData('email', e.target.value)}
                            className="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 outline-none transition focus:border-green-500 focus:ring-2 focus:ring-green-500/20"
                            placeholder="user@example.com"
                            autoComplete="email"
                        />
                    </FormField>

                    <FormField label="Password" required error={errors.password}>
                        <input
                            type="password"
                            value={data.password}
                            onChange={(e) => setData('password', e.target.value)}
                            className="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 outline-none transition focus:border-green-500 focus:ring-2 focus:ring-green-500/20"
                            autoComplete="new-password"
                        />
                    </FormField>

                    <FormField label="Confirm Password" required error={errors.password_confirmation}>
                        <input
                            type="password"
                            value={data.password_confirmation}
                            onChange={(e) => setData('password_confirmation', e.target.value)}
                            className="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 outline-none transition focus:border-green-500 focus:ring-2 focus:ring-green-500/20"
                            autoComplete="new-password"
                        />
                    </FormField>
                </ResourceForm>
            </div>
        </AdminLayout>
    )
}
