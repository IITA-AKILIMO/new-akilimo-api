<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Try the AKILIMO recommendations API — get fertilizer, intercropping, and planting schedule recommendations without an account.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Playground — {{ config('app.name', 'AKILIMO API') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-serif-display:400,400i|dm-sans:300,400,500,600&display=swap" rel="stylesheet" />

    @viteReactRefresh
    @vite(['resources/scss/playground.scss', 'resources/js/playground/index.tsx'])
</head>
<body>

<nav class="pg-nav">
    <a href="/" class="pg-nav-brand">
        <span class="pg-nav-brand-dot"></span>
        {{ config('app.name', 'AKILIMO API') }}
    </a>
    <span class="pg-nav-badge">Playground</span>
</nav>

<div id="playground-root"></div>

</body>
</html>
