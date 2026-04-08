import { useForm } from '@inertiajs/react'
import type { FormEvent } from 'react'

interface LoginForm {
    username: string
    password: string
}

export default function Login() {
    const { data, setData, post, processing, errors } = useForm<LoginForm>({
        username: '',
        password: '',
    })

    function handleSubmit(e: FormEvent) {
        e.preventDefault()
        post('/admin/login')
    }

    return (
        <div className="flex min-h-screen items-center justify-center bg-gray-900 px-4">
            <div className="w-full max-w-sm">
                {/* Brand */}
                <div className="mb-8 text-center">
                    <span className="text-3xl font-bold text-green-400">Akilimo</span>
                    <span className="text-3xl font-light text-white"> Admin</span>
                    <p className="mt-2 text-sm text-gray-400">Sign in to your account</p>
                </div>

                <form
                    onSubmit={handleSubmit}
                    className="rounded-xl bg-white p-8 shadow-xl"
                >
                    <div className="space-y-5">
                        {/* Username */}
                        <div>
                            <label
                                htmlFor="username"
                                className="block text-sm font-medium text-gray-700"
                            >
                                Username or email
                            </label>
                            <input
                                id="username"
                                type="text"
                                autoComplete="username"
                                autoFocus
                                value={data.username}
                                onChange={(e) => setData('username', e.target.value)}
                                className={`mt-1 block w-full rounded-lg border px-3 py-2 text-sm shadow-sm outline-none transition focus:ring-2 focus:ring-green-500 ${
                                    errors.username
                                        ? 'border-red-400 focus:ring-red-400'
                                        : 'border-gray-300'
                                }`}
                            />
                            {errors.username && (
                                <p className="mt-1 text-xs text-red-600">{errors.username}</p>
                            )}
                        </div>

                        {/* Password */}
                        <div>
                            <label
                                htmlFor="password"
                                className="block text-sm font-medium text-gray-700"
                            >
                                Password
                            </label>
                            <input
                                id="password"
                                type="password"
                                autoComplete="current-password"
                                value={data.password}
                                onChange={(e) => setData('password', e.target.value)}
                                className={`mt-1 block w-full rounded-lg border px-3 py-2 text-sm shadow-sm outline-none transition focus:ring-2 focus:ring-green-500 ${
                                    errors.password
                                        ? 'border-red-400 focus:ring-red-400'
                                        : 'border-gray-300'
                                }`}
                            />
                            {errors.password && (
                                <p className="mt-1 text-xs text-red-600">{errors.password}</p>
                            )}
                        </div>

                        <button
                            type="submit"
                            disabled={processing}
                            className="w-full rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 disabled:opacity-60"
                        >
                            {processing ? 'Signing in…' : 'Sign in'}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    )
}
