<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Account') · Prime Byte</title>
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
        :root { --font: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; --radius-sm: 10px; }
        :root[data-theme="light"] { --bg:#f4f6fb; --surface:#fff; --surface-3:#eef2f8; --text:#0f172a; --text-soft:#334155; --muted:#64748b; --border:#d5dce7; --primary:#4f46e5; --primary-600:#4338ca; --primary-soft:#eef0fe; --danger:#dc2626; --success:#16a34a; --success-soft:#e7f6ee; --shadow:0 20px 60px rgba(16,24,40,0.12); }
        :root[data-theme="dark"] { --bg:#0a0f1c; --surface:#111827; --surface-3:#1c2740; --text:#e8edf7; --text-soft:#c3cddd; --muted:#8b9ab4; --border:#2a3752; --primary:#6366f1; --primary-600:#818cf8; --primary-soft:rgba(99,102,241,0.14); --danger:#f87171; --success:#34d399; --success-soft:rgba(52,211,153,0.14); --shadow:0 24px 70px rgba(0,0,0,0.5); }
        * { box-sizing: border-box; }
        body { margin:0; font-family:var(--font); min-height:100vh; background:var(--bg); color:var(--text); display:grid; place-items:center; padding:24px; }
        .theme-toggle { position:fixed; top:20px; right:20px; width:42px; height:42px; border-radius:10px; border:1px solid var(--border); background:var(--surface); color:var(--text-soft); cursor:pointer; display:grid; place-items:center; }
        .theme-toggle svg { width:20px; height:20px; }
        .theme-toggle .sun { display:none; }
        :root[data-theme="dark"] .theme-toggle .sun { display:block; }
        :root[data-theme="dark"] .theme-toggle .moon { display:none; }
        .auth-card { width:min(100%,440px); background:var(--surface); border:1px solid var(--border); border-radius:18px; box-shadow:var(--shadow); padding:36px; }
        .brand { display:flex; align-items:center; gap:11px; margin-bottom:22px; }
        .brand-logo { width:42px; height:42px; border-radius:11px; background:linear-gradient(135deg,var(--primary),#8b5cf6); display:grid; place-items:center; color:#fff; font-weight:800; }
        .brand-name { font-weight:700; }
        .brand-sub { font-size:0.78rem; color:var(--muted); }
        h1 { font-size:1.35rem; margin:0 0 6px; font-weight:800; }
        .lead { color:var(--muted); font-size:0.9rem; margin:0 0 22px; }
        form { display:grid; gap:16px; }
        .field { display:grid; gap:7px; }
        .field label { font-size:0.84rem; font-weight:600; color:var(--text-soft); }
        .req { color:var(--danger); font-weight:700; }
        .input { width:100%; padding:11px 13px; border-radius:var(--radius-sm); border:1px solid var(--border); background:var(--surface); color:var(--text); font-family:inherit; font-size:0.92rem; outline:none; transition:border-color .15s, box-shadow .15s; }
        .input:focus { border-color:var(--primary); box-shadow:0 0 0 3px var(--primary-soft); }
        .input-wrap { position:relative; }
        .input.has-toggle { padding-right:42px; }
        .pw-toggle { position:absolute; right:7px; top:50%; transform:translateY(-50%); width:32px; height:32px; border:none; background:none; color:var(--muted); cursor:pointer; display:grid; place-items:center; border-radius:8px; }
        .pw-toggle:hover { background:var(--surface-3); color:var(--text); }
        .pw-toggle svg { width:18px; height:18px; }
        .pw-toggle .eye-off { display:none; }
        .pw-toggle.show .eye { display:none; }
        .pw-toggle.show .eye-off { display:block; }
        .btn { border:none; border-radius:var(--radius-sm); padding:12px 18px; background:var(--primary); color:#fff; font-family:inherit; font-weight:700; font-size:0.95rem; cursor:pointer; transition:background .15s; }
        .btn:hover { background:var(--primary-600); }
        .error { color:var(--danger); font-size:0.85rem; }
        .alert { padding:11px 14px; border-radius:var(--radius-sm); font-size:0.86rem; margin-bottom:18px; background:var(--success-soft); color:var(--success); }
        .foot { margin-top:20px; font-size:0.88rem; color:var(--muted); text-align:center; }
        .foot a { color:var(--primary); text-decoration:none; font-weight:600; }
    </style>
</head>
<body>
    <button class="theme-toggle" onclick="toggleTheme()" aria-label="Toggle theme">
        <svg class="moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
        <svg class="sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
    </button>

    <div class="auth-card">
        <div class="brand">
            <div class="brand-logo">PB</div>
            <div>
                <div class="brand-name">Prime Byte</div>
                <div class="brand-sub">Software Solution</div>
            </div>
        </div>
        @yield('content')
    </div>

    <script>
        function toggleTheme() {
            var cur = document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
            var next = cur === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', next);
            try { localStorage.setItem('pb-theme', next); } catch (e) {}
        }
        document.querySelectorAll('.pw-toggle').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var input = document.getElementById(btn.getAttribute('data-target'));
                if (!input) return;
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                btn.classList.toggle('show', show);
            });
        });
    </script>
</body>
</html>
