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
        <div className="login-page">
            <div className="login-slides" aria-hidden="true">
                <div className="login-slide login-slide--1" />
                <div className="login-slide login-slide--2" />
                <div className="login-slide login-slide--3" />
                <div className="login-slide login-slide--4" />
            </div>
            <div className="login-overlay" />

            <div className="login-card">
                <div className="login-logo-wrap">
                    <img src="/images/akilimo_logo_white.png" alt="Akilimo" className="login-logo" />
                </div>

                <div className="login-heading">
                    <h1 className="login-title">Welcome back</h1>
                    <p className="login-sub">Sign in to the admin portal</p>
                </div>

                <form onSubmit={handleSubmit} noValidate className="login-form">
                    <div className="login-field">
                        <label htmlFor="username" className="login-label">
                            Username or email
                        </label>
                        <input
                            id="username"
                            type="text"
                            autoComplete="username"
                            autoFocus
                            placeholder="you@example.com"
                            value={data.username}
                            onChange={(e) => setData('username', e.target.value)}
                            className={`login-input${errors.username ? ' is-error' : ''}`}
                        />
                        {errors.username && (
                            <span className="login-error">{errors.username}</span>
                        )}
                    </div>

                    <div className="login-field">
                        <label htmlFor="password" className="login-label">
                            Password
                        </label>
                        <input
                            id="password"
                            type="password"
                            autoComplete="current-password"
                            placeholder="••••••••"
                            value={data.password}
                            onChange={(e) => setData('password', e.target.value)}
                            className={`login-input${errors.password ? ' is-error' : ''}`}
                        />
                        {errors.password && (
                            <span className="login-error">{errors.password}</span>
                        )}
                    </div>

                    <button
                        type="submit"
                        disabled={processing}
                        className="login-btn"
                    >
                        {processing ? (
                            <span className="login-spinner" role="status" aria-hidden="true" />
                        ) : (
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={2} strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3" />
                            </svg>
                        )}
                        {processing ? 'Signing in…' : 'Sign in'}
                    </button>
                </form>
            </div>
        </div>
    )
}
