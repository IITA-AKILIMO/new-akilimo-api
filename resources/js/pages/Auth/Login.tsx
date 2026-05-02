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
        <div className="min-vh-100 d-flex align-items-center justify-content-center bg-light">
            <div style={{ width: '100%', maxWidth: 420 }} className="px-3">
                <div className="text-center mb-4">
                    <div>
                        <span className="fs-3 fw-bold text-success">Akilimo</span>
                        <span className="fs-3 fw-light text-secondary ms-1">Admin</span>
                    </div>
                    <p className="text-muted small mt-1">Sign in to your account</p>
                </div>

                <div className="card shadow-sm">
                    <div className="card-body p-4">
                        <form onSubmit={handleSubmit} noValidate>
                            <div className="mb-3">
                                <label htmlFor="username" className="form-label fw-medium">
                                    Username or email
                                </label>
                                <input
                                    id="username"
                                    type="text"
                                    autoComplete="username"
                                    autoFocus
                                    value={data.username}
                                    onChange={(e) => setData('username', e.target.value)}
                                    className={`form-control ${errors.username ? 'is-invalid' : ''}`}
                                />
                                {errors.username && (
                                    <div className="invalid-feedback">{errors.username}</div>
                                )}
                            </div>

                            <div className="mb-4">
                                <label htmlFor="password" className="form-label fw-medium">
                                    Password
                                </label>
                                <input
                                    id="password"
                                    type="password"
                                    autoComplete="current-password"
                                    value={data.password}
                                    onChange={(e) => setData('password', e.target.value)}
                                    className={`form-control ${errors.password ? 'is-invalid' : ''}`}
                                />
                                {errors.password && (
                                    <div className="invalid-feedback">{errors.password}</div>
                                )}
                            </div>

                            <button
                                type="submit"
                                disabled={processing}
                                className="btn btn-success w-100"
                            >
                                {processing && (
                                    <span
                                        className="spinner-border spinner-border-sm me-2"
                                        role="status"
                                        aria-hidden="true"
                                    />
                                )}
                                Sign in
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    )
}
