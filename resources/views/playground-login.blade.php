<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign in — {{ config('app.name', 'AKILIMO API') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-serif-display:400,400i|dm-sans:300,400,500,600&display=swap" rel="stylesheet" />
    @viteReactRefresh
    @vite(['resources/scss/playground.scss'])
</head>
<body>

<nav class="pg-nav">
    <a href="/" class="pg-nav-brand">
        <span class="pg-nav-brand-dot"></span>
        {{ config('app.name', 'AKILIMO API') }}
    </a>
    <span class="pg-nav-badge">Playground</span>
</nav>

<div class="pg-auth-wrap">
    <div class="pg-auth-card">
        <div class="pg-auth-heading">
            <h1 class="pg-auth-title">Welcome back</h1>
            <p class="pg-auth-sub">Sign in to access the playground</p>
        </div>

        @if ($errors->any())
            <div class="pg-auth-alert">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="/playground/login" novalidate>
            @csrf

            <div class="pg-auth-field">
                <label for="email" class="pg-auth-label">Email address</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    autocomplete="email"
                    autofocus
                    value="{{ old('email') }}"
                    class="pg-auth-input{{ $errors->has('email') ? ' pg-auth-input--error' : '' }}"
                    required
                />
                @error('email')
                    <span class="pg-auth-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="pg-auth-field">
                <label for="password" class="pg-auth-label">Password</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    autocomplete="current-password"
                    class="pg-auth-input{{ $errors->has('password') ? ' pg-auth-input--error' : '' }}"
                    required
                />
                @error('password')
                    <span class="pg-auth-error">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="pg-auth-btn">Sign in</button>
        </form>

        <p class="pg-auth-switch">
            Don't have an account?
            <a href="/playground/register">Sign up</a>
        </p>
    </div>
</div>

</body>
</html>
