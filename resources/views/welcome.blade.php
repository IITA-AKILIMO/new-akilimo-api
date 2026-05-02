<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Agricultural intelligence API delivering fertilizer, intercropping, and planting schedule recommendations for smallholder farmers across Africa.">
    <title>{{ config('app.name', 'AKILIMO API') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-serif-display:400,400i|dm-sans:300,400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/scss/welcome.scss'])

</head>
<body>

{{-- Navigation --}}
<nav class="nav">
    <a href="/" class="nav-logo">
        <span class="nav-logo-dot"></span>
        {{ config('app.name', 'AKILIMO API') }}
    </a>
    <div class="nav-actions">
        <a href="/health" class="btn btn-outline">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
            </svg>
            Health
        </a>
        <a href="/playground" class="btn btn-terra">Try the API</a>
        <a href="/admin" class="btn btn-primary">Admin Panel</a>
    </div>
</nav>

{{-- Hero --}}
<section class="hero">
    <div class="hero-bg">
        <div class="hero-grid"></div>
    </div>
    <div class="hero-content">
        <div class="hero-eyebrow">
            <span class="hero-eyebrow-dot"></span>
            Agricultural Intelligence API
        </div>
        <h1 class="hero-title">
            Smart farming starts with<br>
            <em>precise recommendations</em>
        </h1>
        <p class="hero-sub">
            AKILIMO delivers data-driven fertilizer, intercropping, and planting
            schedule recommendations tailored to smallholder farmers across Africa.
        </p>
        <div class="hero-actions">
            <a href="/playground" class="btn btn-terra">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 3l14 9-14 9V3z"/>
                </svg>
                Try the API
            </a>
            <a href="/admin" class="btn btn-primary">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                    <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                </svg>
                Admin Panel
            </a>
            <a href="/health" class="btn btn-outline">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                </svg>
                Health Status
            </a>
        </div>
    </div>
</section>

{{-- Stats bar --}}
<div class="stats-bar">
    <div class="stats-bar-inner">
        <div class="stat-item">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
            <div>
                <div class="stat-value">Bearer + API Key</div>
                <div class="stat-label">Authentication methods</div>
            </div>
        </div>
        <div class="stat-item">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <line x1="2" y1="12" x2="22" y2="12"/>
                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
            </svg>
            <div>
                <div class="stat-value">Multi-country</div>
                <div class="stat-label">Regional coverage</div>
            </div>
        </div>
        <div class="stat-item">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
            </svg>
            <div>
                <div class="stat-value">Real-time Compute</div>
                <div class="stat-label">With intelligent caching</div>
            </div>
        </div>
        <div class="stat-item">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="16 18 22 12 16 6"/>
                <polyline points="8 6 2 12 8 18"/>
            </svg>
            <div>
                <div class="stat-value">RESTful JSON</div>
                <div class="stat-label">API standard</div>
            </div>
        </div>
    </div>
</div>

{{-- Features --}}
<section class="section">
    <div class="section-inner">
        <div class="section-label">Core Capabilities</div>
        <h2 class="section-title">Everything farmers need to grow smarter</h2>
        <p class="section-desc">
            From soil nutrient analysis to seasonal planning, AKILIMO covers the full
            spectrum of agricultural intelligence for African smallholder farmers.
        </p>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon feature-icon--terra">🌱</div>
                <h3 class="feature-title">Fertilizer Recommendations</h3>
                <p class="feature-desc">Precise fertilizer types, quantities, and application timing based on crop type, soil data, and local market prices.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon feature-icon--green">🌿</div>
                <h3 class="feature-title">Crop Intercropping</h3>
                <p class="feature-desc">Optimized intercropping combinations for maize, cassava, and potatoes to maximise yield per hectare.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon feature-icon--harvest">📅</div>
                <h3 class="feature-title">Planting Schedules</h3>
                <p class="feature-desc">Data-driven planting calendars tailored to regional climate patterns and local market demand cycles.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon feature-icon--sage">🌍</div>
                <h3 class="feature-title">Multi-country Support</h3>
                <p class="feature-desc">Country-specific fertilizer catalogs, currencies, and commodity price data across multiple African nations.</p>
            </div>
        </div>
    </div>
</section>

{{-- API Showcase --}}
<section class="api-section">
    <div class="api-layout">
        <div>
            <div class="section-label">Developer API</div>
            <h2 class="section-title" style="max-width:320px;">Simple integration, powerful results</h2>
            <p class="section-desc" style="margin-bottom:1.25rem;">
                A single POST endpoint delivers complete farm recommendations.
                Authenticate with a short-lived bearer token or a long-lived API key.
            </p>

            <div class="auth-methods">
                <div class="auth-method">
                    <div class="auth-method-icon">🔑</div>
                    <div>
                        <div class="auth-method-title">Bearer Token</div>
                        <div class="auth-method-desc">
                            Obtain via <code>POST /v1/auth/login</code>, then pass as
                            <code>Authorization: Bearer &lt;token&gt;</code> on every request.
                            Tokens expire after a configurable TTL.
                        </div>
                    </div>
                </div>
                <div class="auth-method">
                    <div class="auth-method-icon">🗝️</div>
                    <div>
                        <div class="auth-method-title">API Key</div>
                        <div class="auth-method-desc">
                            Long-lived key with scoped abilities. Pass as <code>X-Api-Key: ak_…</code>.
                            Generated and managed through the admin panel.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="code-block">
            <div class="code-header">
                <div class="code-dot" style="background:#ff5f57"></div>
                <div class="code-dot" style="background:#febc2e"></div>
                <div class="code-dot" style="background:#28c840"></div>
            </div>
            <div class="code-body"><span class="cc"># Compute farm recommendations</span>
<span class="cm">POST</span> <span class="cu">/v1/recommendations/compute</span>

<span class="chk">Authorization:</span> <span class="chv">Bearer &lt;token&gt;</span>
<span class="chk">Content-Type:</span>  <span class="chv">application/json</span>

{
  <span class="ck">"country"</span>:        <span class="cs">"NG"</span>,
  <span class="ck">"use_case"</span>:       <span class="cs">"FR"</span>,
  <span class="ck">"crop_type"</span>:      <span class="cs">"maize"</span>,
  <span class="ck">"farm_size"</span>:      <span class="cn">2.5</span>,
  <span class="ck">"currency"</span>:       <span class="cs">"NGN"</span>,
  <span class="ck">"fertilizer_list"</span>: [
    {
      <span class="ck">"key"</span>:      <span class="cs">"urea"</span>,
      <span class="ck">"selected"</span>: <span class="cb">true</span>,
      <span class="ck">"weight"</span>:   <span class="cn">50</span>,
      <span class="ck">"price"</span>:    <span class="cn">12000</span>
    }
  ]
}

<span class="cc"># ← Returns optimised fertilizer rates,</span>
<span class="cc">#   intercrop options &amp; planting calendar</span></div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="cta-section">
    <div class="cta-inner">
        <h2 class="cta-title">Ready to get started?</h2>
        <p class="cta-desc">
            Try the API instantly in the playground — no account needed.
            Or sign in to the admin panel to manage data and generate API keys.
        </p>
        <div class="cta-actions">
            <a href="/playground" class="btn btn-terra">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 3l14 9-14 9V3z"/>
                </svg>
                Try the API
            </a>
            <a href="/admin/login" class="btn btn-primary">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                    <polyline points="10 17 15 12 10 7"/>
                    <line x1="15" y1="12" x2="3" y2="12"/>
                </svg>
                Sign in to Admin
            </a>
            <a href="/health" class="btn btn-outline">View API Health</a>
        </div>
    </div>
</section>

{{-- Footer --}}
<footer class="footer">
    <p>
        &copy; {{ date('Y') }} {{ config('app.name', 'AKILIMO API') }}
        &mdash; Agricultural recommendations for smallholder farmers
        &mdash; <a href="/health">System Status</a>
    </p>
</footer>

</body>
</html>
