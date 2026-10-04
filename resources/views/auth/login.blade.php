<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#071126">
    <title>Sign in · InventoryOS</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="auth-page">
    <div class="auth-orb auth-orb-one"></div>
    <div class="auth-orb auth-orb-two"></div>
    <main class="auth-layout">
        <section class="auth-showcase">
            <a href="{{ url('/') }}" class="auth-brand" aria-label="InventoryOS">
                <img src="{{ asset('logo.svg') }}" alt="" class="auth-brand-logo">
                <span><strong>Inventory<span>OS</span></strong><small>Operations platform</small></span>
            </a>
            <div class="auth-showcase-copy">
                <span class="eyebrow">SMART OPERATIONS</span>
                <h1>Run your inventory with clarity.</h1>
                <p>One workspace for stock, sales, purchasing, warehouses and operational reporting.</p>
            </div>
            <div class="auth-feature-grid">
                <div class="auth-feature"><span class="auth-feature-icon">▣</span><div><strong>Inventory control</strong><small>Track stock across every warehouse.</small></div></div>
                <div class="auth-feature"><span class="auth-feature-icon">↗</span><div><strong>Live operations</strong><small>POS and movement activity in one view.</small></div></div>
                <div class="auth-feature"><span class="auth-feature-icon">✓</span><div><strong>Security first</strong><small>RBAC, CSRF, audit trails and rate limits.</small></div></div>
            </div>
            <div class="auth-showcase-footer"><span class="auth-status-dot"></span> InventoryOS local workspace <span>·</span> v1.0</div>
        </section>

        <section class="auth-card">
            <div class="auth-card-top">
                <span class="auth-mini-icon">↳</span>
                <span>Authorized access</span>
            </div>
            <div class="auth-copy">
                <span class="eyebrow">WELCOME BACK</span>
                <h2>Sign in to InventoryOS</h2>
                <p>Enter your administrator or staff credentials to continue.</p>
            </div>

            @if($errors->any())
                <div class="auth-error" role="alert">
                    <strong>Sign-in failed</strong>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ url('/login') }}" class="auth-form">
                @csrf
                <label class="auth-field">
                    <span>Email address</span>
                    <div class="auth-input-wrap"><span class="auth-input-icon">@</span><input name="email" type="email" autocomplete="username" required autofocus value="{{ old('email') }}" placeholder="admin@inventory.test"></div>
                </label>
                <label class="auth-field">
                    <span>Password</span>
                    <div class="auth-input-wrap"><span class="auth-input-icon">●</span><input name="password" id="loginPassword" type="password" autocomplete="current-password" required placeholder="Enter your password"><button type="button" class="password-toggle" id="togglePassword" aria-label="Show password">Show</button></div>
                </label>
                <div class="auth-options"><label class="remember"><input type="checkbox" name="remember" value="1"><span>Keep me signed in</span></label><span class="auth-security">Protected session</span></div>
                <button class="auth-submit" type="submit"><span>Sign in</span><span>→</span></button>
            </form>

            <div class="auth-security-note"><span>⌁</span><div><strong>Protected workspace</strong><small>Authentication is rate-limited and sessions are regenerated after sign-in.</small></div></div>
        </section>
    </main>
    <script>
        const toggle=document.getElementById('togglePassword');
        const password=document.getElementById('loginPassword');
        toggle?.addEventListener('click',()=>{const visible=password.type==='text';password.type=visible?'password':'text';toggle.textContent=visible?'Show':'Hide';toggle.setAttribute('aria-label',visible?'Show password':'Hide password');});
    </script>
</body>
</html>
