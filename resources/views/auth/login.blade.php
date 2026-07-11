<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in · Prime Byte</title>
    <script>
        (function () {
            try {
                var t = localStorage.getItem('pb-theme');
                if (!t) t = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                document.documentElement.setAttribute('data-theme', t);
            } catch (e) {}
        })();
    </script>
    <style>
        :root {
            --font: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            --radius-sm: 10px;
        }
        :root[data-theme="light"] {
            --bg: #f4f6fb; --surface: #ffffff; --surface-3: #eef2f8;
            --text: #0f172a; --text-soft: #334155; --muted: #64748b; --border: #d5dce7;
            --primary: #4f46e5; --primary-600: #4338ca; --primary-soft: #eef0fe; --danger: #dc2626;
            --shadow: 0 20px 60px rgba(16,24,40,0.12);
        }
        :root[data-theme="dark"] {
            --bg: #0a0f1c; --surface: #111827; --surface-3: #1c2740;
            --text: #e8edf7; --text-soft: #c3cddd; --muted: #8b9ab4; --border: #2a3752;
            --primary: #6366f1; --primary-600: #818cf8; --primary-soft: rgba(99,102,241,0.14); --danger: #f87171;
            --shadow: 0 24px 70px rgba(0,0,0,0.5);
        }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: var(--font); min-height: 100vh; background: var(--bg); color: var(--text); display: grid; place-items: center; padding: 24px; }
        .theme-toggle { position: fixed; top: 20px; right: 20px; width: 42px; height: 42px; border-radius: 10px; border: 1px solid var(--border); background: var(--surface); color: var(--text-soft); cursor: pointer; display: grid; place-items: center; z-index: 5; }
        .theme-toggle svg { width: 20px; height: 20px; }
        .theme-toggle .sun { display: none; }
        :root[data-theme="dark"] .theme-toggle .sun { display: block; }
        :root[data-theme="dark"] .theme-toggle .moon { display: none; }

        .card { width: min(100%, 940px); display: grid; grid-template-columns: 1.05fr 0.95fr; background: var(--surface); border: 1px solid var(--border); border-radius: 20px; overflow: hidden; box-shadow: var(--shadow); }
        .hero { padding: 44px; background: linear-gradient(160deg, #1e1b4b 0%, #24225c 45%, #312e81 100%); color: #eef1ff; display: flex; flex-direction: column; justify-content: space-between; gap: 32px; position: relative; overflow: hidden; }
        .hero::after { content: ''; position: absolute; top: -80px; right: -80px; width: 260px; height: 260px; border-radius: 50%; background: radial-gradient(circle, rgba(99,102,241,0.35), transparent 70%); pointer-events: none; }
        .hero > * { position: relative; z-index: 1; }
        .brand { display: flex; align-items: center; gap: 12px; }
        .brand-logo { width: 44px; height: 44px; border-radius: 12px; background: rgba(255,255,255,0.18); display: grid; place-items: center; font-weight: 800; font-size: 1.1rem; }
        .brand-name { font-weight: 700; font-size: 1.05rem; }
        .brand-sub { font-size: 0.8rem; opacity: 0.8; }
        .hero h1 { font-size: 2rem; margin: 0 0 12px; font-weight: 800; letter-spacing: -0.02em; }
        .hero p { margin: 0; opacity: 0.9; line-height: 1.65; font-size: 0.96rem; }
        .features { display: grid; gap: 12px; }
        .feature { display: flex; align-items: center; gap: 11px; font-size: 0.9rem; }
        .feature .tick { width: 24px; height: 24px; border-radius: 7px; background: rgba(255,255,255,0.18); display: grid; place-items: center; flex-shrink: 0; }
        .feature svg { width: 14px; height: 14px; }

        .form-panel { padding: 44px; display: flex; flex-direction: column; justify-content: center; }
        .form-panel h2 { font-size: 1.4rem; font-weight: 800; margin: 0 0 6px; letter-spacing: -0.01em; }
        .form-panel .lead { color: var(--muted); font-size: 0.92rem; margin: 0 0 26px; }
        form { display: grid; gap: 16px; }
        .field { display: grid; gap: 7px; }
        .field label { font-size: 0.84rem; font-weight: 600; color: var(--text-soft); }
        .input-wrap { position: relative; }
        .input-wrap svg { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); width: 17px; height: 17px; color: var(--muted); }
        .input-wrap input { width: 100%; padding: 12px 14px 12px 40px; border-radius: var(--radius-sm); border: 1px solid var(--border); background: var(--surface); color: var(--text); font-family: inherit; font-size: 0.92rem; outline: none; transition: border-color .15s, box-shadow .15s; }
        .input-wrap input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-soft); }
        .row { display: flex; align-items: center; justify-content: space-between; font-size: 0.86rem; color: var(--muted); }
        .row label { display: flex; align-items: center; gap: 8px; cursor: pointer; }
        .btn { border: none; border-radius: var(--radius-sm); padding: 13px 18px; background: var(--primary); color: #fff; font-family: inherit; font-weight: 700; font-size: 0.95rem; cursor: pointer; transition: background .15s; }
        .btn:hover { background: var(--primary-600); }
        .error { color: var(--danger); font-size: 0.85rem; }
        .seed { margin-top: 20px; font-size: 0.82rem; color: var(--muted); padding: 12px 14px; background: var(--surface-3); border-radius: var(--radius-sm); }
        @media (max-width: 820px) { .card { grid-template-columns: 1fr; } .hero { order: 2; } }
        @media (max-width: 480px) { .hero, .form-panel { padding: 28px; } }
    </style>
</head>
<body>
    <button class="theme-toggle" onclick="toggleTheme()" aria-label="Toggle theme">
        <svg class="moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
        <svg class="sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
    </button>

    <div class="card">
        <section class="hero">
            <div class="brand">
                <div class="brand-logo">PB</div>
                <div>
                    <div class="brand-name">Prime Byte</div>
                    <div class="brand-sub">Software Solution</div>
                </div>
            </div>
            <div>
                <h1>Accounting, simplified.</h1>
                <p>Manage clients, projects, bank records and invoices from one focused workspace built for fast decisions.</p>
            </div>
            <div class="features">
                <div class="feature"><span class="tick"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span> Live financial reports</div>
                <div class="feature"><span class="tick"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span> Secure, protected access</div>
                <div class="feature"><span class="tick"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span> Invoices & PDF export</div>
            </div>
        </section>

        <section class="form-panel">
            <h2>Sign in</h2>
            <p class="lead">Enter your credentials to access the control panel.</p>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="field">
                    <label>Email address</label>
                    <div class="input-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autocomplete="email">
                    </div>
                    @error('email')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="field">
                    <label>Password</label>
                    <div class="input-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <input type="password" name="password" placeholder="••••••••" required autocomplete="current-password">
                    </div>
                    @error('password')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="row">
                    <label><input type="checkbox" name="remember" value="1"> Keep me signed in</label>
                </div>
                <button class="btn" type="submit">Sign in</button>
            </form>
            <div class="seed">Seeded admin — admin@example.com / admin12345</div>
        </section>
    </div>

    <script>
        function toggleTheme() {
            var cur = document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
            var next = cur === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', next);
            try { localStorage.setItem('pb-theme', next); } catch (e) {}
        }
    </script>
</body>
</html>
