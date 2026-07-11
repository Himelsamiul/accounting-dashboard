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
        :root { --font: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; --radius-sm: 12px; }
        :root[data-theme="light"] {
            --bg: #eef1f8; --surface: #ffffff; --surface-3: #f1f4fa;
            --text: #0f172a; --text-soft: #334155; --muted: #64748b; --border: #dde3ee;
            --primary: #4f46e5; --primary-600: #4338ca; --primary-soft: #eef0fe; --danger: #dc2626;
            --shadow: 0 30px 80px rgba(30,27,75,0.18);
        }
        :root[data-theme="dark"] {
            --bg: #070b16; --surface: #0f1626; --surface-3: #1a2336;
            --text: #e8edf7; --text-soft: #c3cddd; --muted: #8b9ab4; --border: #24304a;
            --primary: #6366f1; --primary-600: #818cf8; --primary-soft: rgba(99,102,241,0.16); --danger: #f87171;
            --shadow: 0 30px 90px rgba(0,0,0,0.6);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0; font-family: var(--font); min-height: 100vh; color: var(--text);
            background:
                radial-gradient(1100px 600px at 10% 0%, rgba(99,102,241,0.16), transparent 55%),
                radial-gradient(900px 600px at 100% 100%, rgba(139,92,246,0.16), transparent 55%),
                var(--bg);
            display: grid; place-items: center; padding: 28px;
        }
        .theme-toggle { position: fixed; top: 22px; right: 22px; width: 44px; height: 44px; border-radius: 12px; border: 1px solid var(--border); background: var(--surface); color: var(--text-soft); cursor: pointer; display: grid; place-items: center; z-index: 5; box-shadow: 0 6px 18px rgba(0,0,0,0.08); }
        .theme-toggle svg { width: 20px; height: 20px; }
        .theme-toggle .sun { display: none; }
        :root[data-theme="dark"] .theme-toggle .sun { display: block; }
        :root[data-theme="dark"] .theme-toggle .moon { display: none; }

        .card {
            width: min(100%, 1080px); min-height: 640px; display: grid; grid-template-columns: 1.08fr 0.92fr;
            background: var(--surface); border: 1px solid var(--border); border-radius: 28px; overflow: hidden; box-shadow: var(--shadow);
        }

        /* Hero */
        .hero {
            position: relative; overflow: hidden; padding: 52px 48px; color: #eef1ff;
            background: linear-gradient(155deg, #312e81 0%, #4338ca 46%, #6d28d9 100%);
            display: flex; flex-direction: column; justify-content: space-between; gap: 34px;
        }
        .hero::before { content: ''; position: absolute; top: -120px; right: -120px; width: 340px; height: 340px; border-radius: 50%; background: radial-gradient(circle, rgba(255,255,255,0.18), transparent 70%); }
        .hero::after { content: ''; position: absolute; bottom: -140px; left: -80px; width: 300px; height: 300px; border-radius: 50%; background: radial-gradient(circle, rgba(139,92,246,0.45), transparent 70%); }
        .hero > * { position: relative; z-index: 1; }
        .brand { display: flex; align-items: center; gap: 13px; }
        .brand-logo { width: 50px; height: 50px; border-radius: 14px; background: rgba(255,255,255,0.16); backdrop-filter: blur(4px); display: grid; place-items: center; font-weight: 800; font-size: 1.2rem; border: 1px solid rgba(255,255,255,0.2); }
        .brand-name { font-weight: 800; font-size: 1.1rem; }
        .brand-sub { font-size: 0.82rem; opacity: 0.82; }
        .hero-headline h1 { font-size: clamp(1.9rem, 3vw, 2.5rem); margin: 0 0 14px; font-weight: 800; letter-spacing: -0.02em; line-height: 1.15; }
        .hero-headline p { margin: 0; opacity: 0.9; line-height: 1.7; font-size: 1rem; max-width: 90%; }
        .features { display: grid; gap: 14px; }
        .feature { display: flex; align-items: center; gap: 13px; font-size: 0.96rem; }
        .feature .tick { width: 28px; height: 28px; border-radius: 9px; background: rgba(255,255,255,0.16); display: grid; place-items: center; flex-shrink: 0; border: 1px solid rgba(255,255,255,0.18); }
        .feature svg { width: 15px; height: 15px; }
        .testimonial { background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.16); border-radius: 16px; padding: 18px 20px; backdrop-filter: blur(6px); }
        .testimonial p { margin: 0 0 10px; font-size: 0.92rem; line-height: 1.6; opacity: 0.95; }
        .testimonial .who { display: flex; align-items: center; gap: 10px; font-size: 0.85rem; }
        .testimonial .who .av { width: 30px; height: 30px; border-radius: 50%; background: linear-gradient(135deg,#22d3ee,#a78bfa); display: grid; place-items: center; font-weight: 800; font-size: 0.8rem; color: #10121f; }
        .stars { color: #fcd34d; letter-spacing: 2px; font-size: 0.85rem; }

        /* Form */
        .form-panel { padding: 56px 52px; display: flex; flex-direction: column; justify-content: center; }
        .form-panel h2 { font-size: 1.7rem; font-weight: 800; margin: 0 0 8px; letter-spacing: -0.02em; }
        .form-panel .lead { color: var(--muted); font-size: 0.96rem; margin: 0 0 30px; }
        form { display: grid; gap: 20px; }
        .field { display: grid; gap: 8px; }
        .field label { font-size: 0.86rem; font-weight: 600; color: var(--text-soft); }
        .req { color: var(--danger); font-weight: 700; }
        .input-wrap { position: relative; }
        .input-wrap > svg.lead-icon { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; color: var(--muted); }
        .input-wrap input { width: 100%; padding: 15px 15px 15px 46px; border-radius: var(--radius-sm); border: 1px solid var(--border); background: var(--surface); color: var(--text); font-family: inherit; font-size: 0.98rem; outline: none; transition: border-color .15s, box-shadow .15s; }
        .input-wrap input.has-toggle { padding-right: 48px; }
        .input-wrap input:focus { border-color: var(--primary); box-shadow: 0 0 0 4px var(--primary-soft); }
        .pw-toggle { position: absolute; right: 8px; top: 50%; transform: translateY(-50%); width: 34px; height: 34px; border: none; background: none; color: var(--muted); cursor: pointer; display: grid; place-items: center; border-radius: 8px; }
        .pw-toggle:hover { background: var(--surface-3); color: var(--text); }
        .pw-toggle svg { width: 19px; height: 19px; }
        .pw-toggle .eye-off { display: none; }
        .pw-toggle.show .eye { display: none; }
        .pw-toggle.show .eye-off { display: block; }
        .row { display: flex; align-items: center; justify-content: space-between; font-size: 0.9rem; color: var(--muted); }
        .row label { display: flex; align-items: center; gap: 9px; cursor: pointer; }
        .forgot-link { color: var(--primary); text-decoration: none; font-weight: 600; }
        .forgot-link:hover { text-decoration: underline; }
        .btn { border: none; border-radius: var(--radius-sm); padding: 15px 18px; background: linear-gradient(135deg, var(--primary), #7c3aed); color: #fff; font-family: inherit; font-weight: 700; font-size: 1rem; cursor: pointer; transition: filter .15s, transform .05s; box-shadow: 0 10px 26px rgba(79,70,229,0.32); }
        .btn:hover { filter: brightness(1.06); }
        .btn:active { transform: translateY(1px); }
        .error { color: var(--danger); font-size: 0.88rem; }
        .signin-foot { margin-top: 26px; font-size: 0.86rem; color: var(--muted); text-align: center; }

        @media (max-width: 880px) {
            .card { grid-template-columns: 1fr; min-height: 0; }
            .hero { order: 2; padding: 36px; }
            .form-panel { padding: 40px 32px; }
        }
        @media (max-width: 480px) { body { padding: 14px; } .hero, .form-panel { padding: 28px 24px; } }
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

            <div class="hero-headline">
                <h1>Run your accounting with confidence.</h1>
                <p>Clients, projects, bank records and invoices — organised in one elegant workspace your whole team can trust.</p>
            </div>

            <div class="features">
                <div class="feature"><span class="tick"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span> Real-time collection &amp; earnings insight</div>
                <div class="feature"><span class="tick"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span> Role-based access for every team member</div>
                <div class="feature"><span class="tick"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span> Professional invoices with PDF &amp; Excel export</div>
            </div>

            <div class="testimonial">
                <div class="stars">★★★★★</div>
                <p>“Clean, fast and reliable. Managing our clients and invoices has never been this simple.”</p>
                <div class="who"><span class="av">JD</span> James Dawson · Director, London</div>
            </div>
        </section>

        <section class="form-panel">
            <h2>Welcome back</h2>
            <p class="lead">Sign in to your Prime Byte control panel.</p>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="field">
                    <label>Email address <span class="req">*</span></label>
                    <div class="input-wrap">
                        <svg class="lead-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autocomplete="email" autofocus>
                    </div>
                    @error('email')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label>Password <span class="req">*</span></label>
                    <div class="input-wrap">
                        <svg class="lead-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <input type="password" name="password" id="password" class="has-toggle" placeholder="••••••••" required autocomplete="current-password">
                        <button type="button" class="pw-toggle" id="pwToggle" onclick="togglePw()" aria-label="Show password" title="Show / hide password">
                            <svg class="eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg class="eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        </button>
                    </div>
                    @error('password')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="row">
                    <label><input type="checkbox" name="remember" value="1"> Keep me signed in</label>
                    <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                </div>

                <button class="btn" type="submit">Sign in</button>
            </form>
            <div class="signin-foot">Protected access · Prime Byte Software Solution</div>
        </section>
    </div>

    <script>
        function toggleTheme() {
            var cur = document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
            var next = cur === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', next);
            try { localStorage.setItem('pb-theme', next); } catch (e) {}
        }
        function togglePw() {
            var input = document.getElementById('password');
            var btn = document.getElementById('pwToggle');
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.classList.toggle('show', show);
        }
    </script>
</body>
</html>
