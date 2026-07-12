<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') · Prime Byte</title>
    <script>
        // Apply saved theme before paint to avoid flash of wrong theme.
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
            --radius: 14px;
            --radius-sm: 10px;
            --sidebar-w: 260px;
        }
        :root[data-theme="light"] {
            --bg: #f4f6fb;
            --surface: #ffffff;
            --surface-2: #f7f9fc;
            --surface-3: #eef2f8;
            --text: #0f172a;
            --text-soft: #334155;
            --muted: #64748b;
            --border: #e5e9f0;
            --border-strong: #d5dce7;
            --primary: #4f46e5;
            --primary-600: #4338ca;
            --primary-soft: #eef0fe;
            --success: #16a34a;
            --success-soft: #e7f6ee;
            --warning: #d97706;
            --warning-soft: #fdf1e2;
            --danger: #dc2626;
            --danger-soft: #fdecec;
            --shadow: 0 1px 2px rgba(16,24,40,0.04), 0 8px 24px rgba(16,24,40,0.06);
            --shadow-sm: 0 1px 2px rgba(16,24,40,0.06);
            --sidebar-bg: #0f172a;
            --sidebar-text: #cbd5e1;
            --sidebar-muted: #64748b;
            --sidebar-active: #ffffff;
            --sidebar-border: rgba(255,255,255,0.08);
        }
        :root[data-theme="dark"] {
            --bg: #0a0f1c;
            --surface: #111827;
            --surface-2: #161f31;
            --surface-3: #1c2740;
            --text: #e8edf7;
            --text-soft: #c3cddd;
            --muted: #8b9ab4;
            --border: #1f2a40;
            --border-strong: #2a3752;
            --primary: #6366f1;
            --primary-600: #818cf8;
            --primary-soft: rgba(99,102,241,0.14);
            --success: #34d399;
            --success-soft: rgba(52,211,153,0.14);
            --warning: #fbbf24;
            --warning-soft: rgba(251,191,36,0.14);
            --danger: #f87171;
            --danger-soft: rgba(248,113,113,0.14);
            --shadow: 0 1px 2px rgba(0,0,0,0.3), 0 12px 32px rgba(0,0,0,0.35);
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.3);
            --sidebar-bg: #0b1120;
            --sidebar-text: #b7c2d6;
            --sidebar-muted: #5f6f8a;
            --sidebar-active: #ffffff;
            --sidebar-border: rgba(255,255,255,0.06);
        }

        * { box-sizing: border-box; }
        html, body { height: 100%; }
        body {
            margin: 0;
            font-family: var(--font);
            background: var(--bg);
            color: var(--text);
            font-size: 14.5px;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }
        a { color: inherit; }
        h1, h2, h3, h4 { margin: 0; font-weight: 700; letter-spacing: -0.01em; }
        ::selection { background: var(--primary-soft); }

        /* Layout shell */
        .app { display: flex; min-height: 100vh; }
        .sidebar {
            width: var(--sidebar-w); flex-shrink: 0; background: var(--sidebar-bg);
            color: var(--sidebar-text); display: flex; flex-direction: column;
            position: sticky; top: 0; height: 100vh; border-right: 1px solid var(--sidebar-border);
        }
        .brand { display: flex; align-items: center; gap: 11px; padding: 22px 20px 18px; }
        .brand-logo { width: 38px; height: 38px; border-radius: 11px; background: linear-gradient(135deg, var(--primary), #8b5cf6); display: grid; place-items: center; color: #fff; font-weight: 800; font-size: 1.05rem; flex-shrink: 0; }
        .brand-name { font-size: 0.98rem; font-weight: 700; color: #fff; line-height: 1.2; }
        .brand-sub { font-size: 0.72rem; color: var(--sidebar-muted); }
        .nav { padding: 8px 12px; flex: 1; overflow-y: auto; display: flex; flex-direction: column; }
        .nav-admin { margin-top: auto; }
        .nav-admin .nav-label { border-top: 1px solid var(--sidebar-border); margin-top: 8px; padding-top: 16px; }
        .nav-label { font-size: 0.68rem; letter-spacing: 0.14em; text-transform: uppercase; color: var(--sidebar-muted); padding: 14px 12px 8px; }
        .nav a {
            display: flex; align-items: center; gap: 12px; padding: 10px 12px; border-radius: var(--radius-sm);
            color: var(--sidebar-text); text-decoration: none; font-weight: 500; font-size: 0.9rem; margin-bottom: 2px;
            transition: background .15s ease, color .15s ease;
        }
        .nav a svg { width: 18px; height: 18px; flex-shrink: 0; opacity: 0.85; }
        .nav a:hover { background: rgba(255,255,255,0.06); color: #fff; }
        .nav a.active { background: var(--primary); color: #fff; }
        .nav a.active svg { opacity: 1; }
        .sidebar-foot { padding: 14px 16px; border-top: 1px solid var(--sidebar-border); font-size: 0.74rem; color: var(--sidebar-muted); }

        .main { flex: 1; display: flex; flex-direction: column; min-width: 0; }
        .topbar {
            display: flex; align-items: center; justify-content: space-between; gap: 16px;
            padding: 14px 26px; background: var(--surface); border-bottom: 1px solid var(--border);
            position: sticky; top: 0; z-index: 20;
        }
        .topbar-title { font-size: 1.02rem; font-weight: 700; }
        .topbar-right { display: flex; align-items: center; gap: 12px; }
        .clock { font-variant-numeric: tabular-nums; font-size: 0.85rem; color: var(--muted); padding: 8px 12px; border-radius: 999px; background: var(--surface-3); font-weight: 600; }
        .icon-btn {
            width: 40px; height: 40px; border-radius: 10px; border: 1px solid var(--border);
            background: var(--surface); color: var(--text-soft); cursor: pointer; display: grid; place-items: center;
            transition: background .15s ease, border-color .15s ease;
        }
        .icon-btn:hover { background: var(--surface-3); border-color: var(--border-strong); }
        .icon-btn svg { width: 19px; height: 19px; }
        .theme-toggle .sun { display: none; }
        :root[data-theme="dark"] .theme-toggle .sun { display: block; }
        :root[data-theme="dark"] .theme-toggle .moon { display: none; }
        .avatar { width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), #8b5cf6); color: #fff; display: grid; place-items: center; font-weight: 700; font-size: 0.85rem; flex-shrink: 0; }
        .user-meta { line-height: 1.25; }
        .user-name { font-size: 0.86rem; font-weight: 700; }
        .user-role { font-size: 0.74rem; color: var(--muted); }
        .perm-matrix input[type="checkbox"] { width: 17px; height: 17px; cursor: pointer; accent-color: var(--primary); }
        .perm-matrix td, .perm-matrix th { white-space: nowrap; }

        /* Header dropdowns (notifications + profile) */
        .hdr-menu { position: relative; }
        .bell-btn { position: relative; }
        .bell-badge {
            position: absolute; top: -5px; right: -5px; min-width: 18px; height: 18px; padding: 0 5px;
            border-radius: 999px; background: var(--danger); color: #fff; font-size: 0.68rem; font-weight: 800;
            display: grid; place-items: center; border: 2px solid var(--surface); line-height: 1;
        }
        .profile-btn { display: flex; align-items: center; gap: 9px; background: none; border: none; cursor: pointer; padding: 3px; border-radius: 999px; }
        .profile-btn:hover { background: var(--surface-3); }
        .profile-btn .caret { color: var(--muted); transition: transform .18s ease; }
        .hdr-menu.open .profile-btn .caret { transform: rotate(180deg); }
        .dropdown {
            position: absolute; top: calc(100% + 10px); right: 0; width: 340px; max-width: calc(100vw - 32px);
            background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius);
            box-shadow: 0 16px 44px rgba(0,0,0,0.22); z-index: 60; overflow: hidden;
            opacity: 0; visibility: hidden; transform: translateY(-8px); transform-origin: top right;
            transition: opacity .16s ease, transform .16s ease, visibility .16s;
        }
        .hdr-menu.open .dropdown { opacity: 1; visibility: visible; transform: translateY(0); }
        .dropdown-head { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 14px 16px; border-bottom: 1px solid var(--border); }
        .dropdown-head h4 { font-size: 0.95rem; }
        .dropdown-head .mark-read { background: none; border: none; color: var(--primary); font-family: inherit; font-size: 0.78rem; font-weight: 700; cursor: pointer; padding: 0; }
        .dropdown-head .mark-read:hover { text-decoration: underline; }
        .dd-scroll { max-height: 360px; overflow-y: auto; }
        .dd-notif { display: flex; align-items: flex-start; gap: 11px; padding: 12px 16px; border-bottom: 1px solid var(--border); text-decoration: none; color: var(--text); transition: background .12s; }
        .dd-notif:last-child { border-bottom: none; }
        .dd-notif:hover { background: var(--surface-2); }
        .dd-notif.unread { background: var(--primary-soft); }
        .dd-ic { width: 36px; height: 36px; border-radius: 10px; display: grid; place-items: center; flex-shrink: 0; }
        .dd-ic svg { width: 17px; height: 17px; }
        .dd-txt { display: flex; flex-direction: column; gap: 2px; min-width: 0; flex: 1; }
        .dd-txt .t { font-size: 0.85rem; font-weight: 600; line-height: 1.35; }
        .dd-txt .b { font-size: 0.79rem; color: var(--text-soft); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .dd-txt .tm { font-size: 0.72rem; color: var(--muted); margin-top: 1px; }
        .dd-empty { padding: 34px 20px; text-align: center; color: var(--muted); font-size: 0.86rem; }
        .dd-empty svg { width: 34px; height: 34px; opacity: 0.4; margin-bottom: 8px; }
        .dropdown-foot { padding: 11px 16px; border-top: 1px solid var(--border); text-align: center; }
        .dropdown-foot a { color: var(--primary); font-size: 0.83rem; font-weight: 700; text-decoration: none; }
        .dropdown-foot a:hover { text-decoration: underline; }
        /* Profile dropdown */
        .pd-head { display: flex; align-items: center; gap: 13px; padding: 16px; border-bottom: 1px solid var(--border); }
        .pd-avatar { width: 48px; height: 48px; border-radius: 13px; background: linear-gradient(135deg, var(--primary), #8b5cf6); color: #fff; display: grid; place-items: center; font-weight: 800; font-size: 1.2rem; flex-shrink: 0; }
        .pd-name { font-weight: 700; font-size: 0.95rem; }
        .pd-role { font-size: 0.76rem; color: var(--primary); font-weight: 600; }
        .pd-email { font-size: 0.78rem; color: var(--muted); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .pd-links { padding: 6px; }
        .pd-link { display: flex; align-items: center; gap: 11px; padding: 10px 12px; border-radius: var(--radius-sm); color: var(--text-soft); text-decoration: none; font-size: 0.88rem; font-weight: 500; width: 100%; background: none; border: none; cursor: pointer; font-family: inherit; text-align: left; }
        .pd-link svg { width: 17px; height: 17px; opacity: 0.8; }
        .pd-link:hover { background: var(--surface-3); color: var(--text); }
        .pd-link.danger { color: var(--danger); }
        .pd-link.danger:hover { background: var(--danger-soft); }
        .pd-divider { height: 1px; background: var(--border); margin: 4px 0; }

        .content { padding: 26px; flex: 1; max-width: 1360px; width: 100%; margin: 0 auto; }

        /* Footer */
        .site-footer {
            padding: 18px 26px; border-top: 1px solid var(--border); background: var(--surface);
            display: flex; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap;
            color: var(--muted); font-size: 0.83rem;
        }
        .site-footer a { color: var(--muted); text-decoration: none; }
        .site-footer a:hover { color: var(--primary); }
        .foot-links { display: flex; gap: 18px; flex-wrap: wrap; }

        /* Page header */
        .page-header { display: flex; align-items: flex-end; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 22px; }
        .page-header h1 { font-size: 1.5rem; }
        .page-header .sub { color: var(--muted); margin-top: 4px; font-size: 0.92rem; }
        .header-actions { display: flex; gap: 10px; flex-wrap: wrap; }

        /* Buttons */
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 7px;
            padding: 10px 16px; border-radius: var(--radius-sm); border: 1px solid transparent;
            font-family: inherit; font-size: 0.88rem; font-weight: 600; cursor: pointer; text-decoration: none;
            transition: background .15s ease, border-color .15s ease, transform .05s ease; white-space: nowrap;
        }
        .btn:active { transform: translateY(1px); }
        .btn svg { width: 16px; height: 16px; }
        .btn-primary { background: var(--primary); color: #fff; box-shadow: var(--shadow-sm); }
        .btn-primary:hover { background: var(--primary-600); }
        .btn-ghost { background: var(--surface); color: var(--text-soft); border-color: var(--border-strong); }
        .btn-ghost:hover { background: var(--surface-3); }
        .btn-danger { background: var(--danger-soft); color: var(--danger); }
        .btn-danger:hover { background: var(--danger); color: #fff; }
        .btn-sm { padding: 7px 12px; font-size: 0.82rem; }

        /* Cards */
        .card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow); }
        .card-pad { padding: 22px; }
        .card-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; padding: 18px 22px; border-bottom: 1px solid var(--border); }
        .card-header h2, .card-header h3 { font-size: 1.02rem; }
        .card-header .sub { color: var(--muted); font-size: 0.85rem; margin-top: 3px; }
        .card-body { padding: 22px; }

        /* Badges */
        .badge { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 999px; font-size: 0.76rem; font-weight: 600; line-height: 1.6; }
        .badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
        .badge-success { background: var(--success-soft); color: var(--success); }
        .badge-warning { background: var(--warning-soft); color: var(--warning); }
        .badge-danger { background: var(--danger-soft); color: var(--danger); }
        .badge-neutral { background: var(--surface-3); color: var(--muted); }
        .badge-primary { background: var(--primary-soft); color: var(--primary); }

        /* Tables */
        .table-wrap { overflow-x: auto; }
        table.table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
        table.table th {
            text-align: left; padding: 12px 16px; font-size: 0.72rem; letter-spacing: 0.06em; text-transform: uppercase;
            color: var(--muted); font-weight: 600; background: var(--surface-2); border-bottom: 1px solid var(--border); white-space: nowrap;
        }
        table.table td { padding: 13px 16px; border-bottom: 1px solid var(--border); color: var(--text-soft); white-space: nowrap; }
        table.table tbody tr:last-child td { border-bottom: none; }
        table.table tbody tr { transition: background .12s ease; }
        table.table tbody tr:hover { background: var(--surface-2); }
        table.table td.strong { color: var(--text); font-weight: 600; }
        .row-actions { display: flex; align-items: center; gap: 6px; }
        .act {
            display: inline-grid; place-items: center; width: 32px; height: 32px; border-radius: 8px;
            color: var(--muted); text-decoration: none; border: 1px solid transparent; background: none; cursor: pointer;
            transition: background .12s ease, color .12s ease;
        }
        .act svg { width: 16px; height: 16px; }
        .act:hover { background: var(--surface-3); color: var(--text); }
        .act.danger:hover { background: var(--danger-soft); color: var(--danger); }

        .empty-state { text-align: center; padding: 48px 20px; color: var(--muted); }
        .empty-state svg { width: 40px; height: 40px; opacity: 0.4; margin-bottom: 10px; }

        /* Forms */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .field { display: grid; gap: 7px; margin-bottom: 16px; }
        .field.col-span { grid-column: 1 / -1; }
        .field label { font-size: 0.83rem; font-weight: 600; color: var(--text-soft); }
        .field .hint { font-size: 0.76rem; color: var(--muted); }
        .req { color: var(--danger); font-weight: 700; margin-left: 1px; }
        .form-legend { font-size: 0.8rem; color: var(--muted); margin-top: 2px; }
        .form-legend .req { margin-right: 3px; }
        .phone-field { display: flex; gap: 8px; }
        .phone-field .phone-code { width: 128px; flex-shrink: 0; padding-right: 28px; }
        .phone-field .phone-number { flex: 1; }
        .input, .select, .textarea {
            width: 100%; padding: 10px 13px; border-radius: var(--radius-sm); border: 1px solid var(--border-strong);
            background: var(--surface); color: var(--text); font-family: inherit; font-size: 0.9rem; outline: none;
            transition: border-color .15s ease, box-shadow .15s ease;
        }
        .input::placeholder, .textarea::placeholder { color: var(--muted); }
        .input:focus, .select:focus, .textarea:focus { border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-soft); }
        .input[readonly] { background: var(--surface-3); color: var(--muted); cursor: default; }
        .textarea { resize: vertical; min-height: 96px; }
        .select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 13px center; padding-right: 34px; }
        .form-actions { display: flex; gap: 10px; margin-top: 4px; }

        /* Toast notifications */
        .toast-wrap { position: fixed; top: 20px; right: 20px; z-index: 200; display: flex; flex-direction: column; gap: 10px; max-width: min(360px, calc(100vw - 40px)); }
        .toast {
            display: flex; align-items: center; gap: 11px; padding: 13px 15px; border-radius: var(--radius-sm);
            background: var(--surface); border: 1px solid var(--border); box-shadow: 0 12px 34px rgba(0,0,0,0.18);
            font-size: 0.9rem; font-weight: 500; color: var(--text);
            transition: opacity .35s ease, transform .35s ease; animation: toastIn .35s ease;
        }
        @keyframes toastIn { from { opacity: 0; transform: translateX(120%); } to { opacity: 1; transform: translateX(0); } }
        .toast svg { width: 20px; height: 20px; flex-shrink: 0; }
        .toast span { flex: 1; }
        .toast-success { border-left: 4px solid var(--success); }
        .toast-success svg { color: var(--success); }
        .toast-danger { border-left: 4px solid var(--danger); }
        .toast-danger svg { color: var(--danger); }
        .toast-close { background: none; border: none; color: var(--muted); font-size: 1.25rem; line-height: 1; cursor: pointer; padding: 0 2px; }
        .toast-close:hover { color: var(--text); }
        /* Live notification popup (clickable) */
        .toast-notif { text-decoration: none; color: var(--text); align-items: flex-start; cursor: pointer; border-left: 4px solid var(--primary); }
        .toast-notif:hover { background: var(--surface-2); }
        .toast-notif .tn-ic { width: 34px; height: 34px; border-radius: 9px; display: grid; place-items: center; flex-shrink: 0; }
        .toast-notif .tn-ic svg { width: 17px; height: 17px; }
        .toast-notif .tn-main { display: flex; flex-direction: column; gap: 2px; min-width: 0; flex: 1; }
        .toast-notif .tn-t { font-weight: 700; font-size: 0.88rem; line-height: 1.35; }
        .toast-notif .tn-b { font-size: 0.8rem; color: var(--text-soft); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

        /* Detail / show pages */
        .detail-list { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1px; background: var(--border); border-radius: var(--radius-sm); overflow: hidden; }
        .detail-row { background: var(--surface); padding: 14px 16px; }
        .detail-row.full { grid-column: 1 / -1; }
        .detail-row .dt { font-size: 0.74rem; letter-spacing: 0.05em; text-transform: uppercase; color: var(--muted); font-weight: 600; margin-bottom: 5px; }
        .detail-row .dd { font-size: 0.95rem; color: var(--text); font-weight: 500; }
        @media (max-width: 560px) { .detail-list { grid-template-columns: 1fr; } }

        /* Dashboard */
        .stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; margin-bottom: 22px; }
        .stat-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 20px; box-shadow: var(--shadow); display: flex; flex-direction: column; gap: 14px; transition: transform .15s ease, border-color .15s ease; }
        .stat-card:hover { transform: translateY(-3px); border-color: var(--border-strong); }
        .stat-top { display: flex; align-items: center; justify-content: space-between; }
        .stat-icon { width: 44px; height: 44px; border-radius: 12px; display: grid; place-items: center; color: #fff; }
        .stat-icon svg { width: 22px; height: 22px; }
        .stat-value { font-size: 1.7rem; font-weight: 800; letter-spacing: -0.02em; line-height: 1; }
        .stat-label { font-size: 0.85rem; color: var(--muted); margin-top: 6px; }
        .stat-sub { font-size: 0.78rem; color: var(--muted); margin-top: 8px; padding-top: 8px; border-top: 1px solid var(--border); }
        .dash-grid { display: grid; grid-template-columns: 1.7fr 1fr; gap: 18px; }
        .chart { display: flex; align-items: flex-end; gap: 16px; height: 210px; padding-top: 24px; }
        .chart-col { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 10px; height: 100%; }
        .chart-track { flex: 1; width: 100%; display: flex; align-items: flex-end; justify-content: center; }
        .chart-bar { width: 62%; max-width: 44px; border-radius: 8px 8px 3px 3px; background: linear-gradient(180deg, var(--primary), #8b5cf6); position: relative; min-height: 6px; transition: filter .15s ease; cursor: default; }
        .chart-bar:hover { filter: brightness(1.1); }
        .chart-tip { position: absolute; top: -24px; left: 50%; transform: translateX(-50%); font-size: 0.72rem; font-weight: 700; color: var(--text); white-space: nowrap; opacity: 0; transition: opacity .15s ease; }
        .chart-bar:hover .chart-tip { opacity: 1; }
        .chart-x { font-size: 0.78rem; color: var(--muted); font-weight: 600; }
        .quick-links { display: grid; gap: 8px; }
        .quick-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }
        .quick-item { display: flex; align-items: center; gap: 12px; padding: 12px 14px; border-radius: var(--radius-sm); text-decoration: none; color: var(--text-soft); border: 1px solid var(--border); transition: background .12s ease, border-color .12s ease; }
        .quick-item:hover { background: var(--surface-2); border-color: var(--border-strong); }
        .quick-item .qi-icon { width: 34px; height: 34px; border-radius: 9px; background: var(--primary-soft); color: var(--primary); display: grid; place-items: center; }
        .quick-item .qi-icon svg { width: 17px; height: 17px; }
        .quick-item .qi-text { flex: 1; font-weight: 600; font-size: 0.9rem; }
        .quick-item .qi-go { color: var(--muted); }

        /* Donut chart */
        .donut-wrap { display: flex; align-items: center; gap: 28px; flex-wrap: wrap; justify-content: center; padding: 8px 0; }
        .donut { width: 168px; height: 168px; border-radius: 50%; position: relative; flex-shrink: 0; }
        .donut::after { content: ''; position: absolute; inset: 26px; background: var(--surface); border-radius: 50%; }
        .donut-center { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; z-index: 1; }
        .donut-num { font-size: 1.8rem; font-weight: 800; letter-spacing: -0.02em; }
        .donut-cap { font-size: 0.76rem; color: var(--muted); }
        .donut-legend { display: grid; gap: 12px; min-width: 150px; }
        .legend-row { display: flex; align-items: center; gap: 10px; font-size: 0.9rem; }
        .legend-dot { width: 11px; height: 11px; border-radius: 3px; flex-shrink: 0; }
        .legend-label { flex: 1; color: var(--text-soft); }
        .legend-val { font-weight: 700; }

        /* Colored cell avatar */
        .cell-name { display: flex; align-items: center; gap: 11px; }
        .cell-avatar { width: 34px; height: 34px; border-radius: 9px; display: grid; place-items: center; color: #fff; font-weight: 700; font-size: 0.82rem; flex-shrink: 0; }
        .val-accent { color: var(--primary); font-weight: 700; }

        /* Search box */
        .search-box { position: relative; }
        .search-box svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--muted); pointer-events: none; }
        .search-box input { padding: 9px 12px 9px 36px; border-radius: var(--radius-sm); border: 1px solid var(--border-strong); background: var(--surface); color: var(--text); font-family: inherit; font-size: 0.88rem; outline: none; width: 240px; max-width: 100%; transition: border-color .15s, box-shadow .15s; }
        .search-box input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-soft); }
        .no-results td { text-align: center; color: var(--muted); padding: 28px 16px; }

        /* Inline progress bar (project collection) */
        .bar-progress { width: 130px; height: 7px; border-radius: 999px; background: var(--surface-3); overflow: hidden; }
        .bar-progress > span { display: block; height: 100%; border-radius: 999px; background: linear-gradient(90deg, var(--primary), #8b5cf6); transition: width .3s ease; }
        .bar-progress.done > span { background: linear-gradient(90deg, #16a34a, #14b8a6); }
        .progress-cell { display: flex; flex-direction: column; gap: 5px; }
        .progress-meta { font-size: 0.76rem; color: var(--muted); }

        /* List + sidebar layout */
        .list-layout { display: grid; grid-template-columns: 1fr 300px; gap: 18px; align-items: start; }
        @media (max-width: 1024px) { .list-layout { grid-template-columns: 1fr; } }

        /* Filter chips toolbar */
        .card-toolbar { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; padding: 13px 18px; border-bottom: 1px solid var(--border); }
        .filter-chips { display: inline-flex; gap: 6px; flex-wrap: wrap; }
        .chip { padding: 7px 14px; border-radius: 999px; border: 1px solid var(--border-strong); background: var(--surface); color: var(--text-soft); font-family: inherit; font-size: 0.83rem; font-weight: 600; cursor: pointer; transition: background .12s, color .12s, border-color .12s; }
        .chip:hover { background: var(--surface-3); }
        .chip.active { background: var(--primary); color: #fff; border-color: var(--primary); }

        /* Horizontal bar chart */
        .hbar-chart { display: grid; gap: 16px; padding: 8px 0; }
        .hbar-row { display: grid; grid-template-columns: 84px 1fr 34px; align-items: center; gap: 12px; }
        .hbar-label { font-size: 0.85rem; color: var(--text-soft); font-weight: 600; }
        .hbar-track { height: 12px; border-radius: 999px; background: var(--surface-3); overflow: hidden; }
        .hbar-fill { height: 100%; border-radius: 999px; transition: width .4s ease; min-width: 4px; }
        .hbar-val { font-size: 0.88rem; font-weight: 700; text-align: right; }

        /* Invoice builder */
        .invoice-layout { display: grid; grid-template-columns: 1fr 340px; gap: 18px; align-items: start; }
        .inv-summary { position: sticky; top: 84px; background: linear-gradient(160deg, var(--primary), #7c3aed); color: #fff; border-radius: var(--radius); padding: 24px; box-shadow: var(--shadow); }
        .inv-summary h3 { font-size: 0.8rem; letter-spacing: 0.08em; text-transform: uppercase; opacity: 0.85; font-weight: 600; }
        .inv-total { font-size: 2.1rem; font-weight: 800; letter-spacing: -0.02em; margin: 6px 0 18px; }
        .inv-line { display: flex; align-items: center; justify-content: space-between; padding: 11px 0; border-top: 1px solid rgba(255,255,255,0.18); font-size: 0.92rem; }
        .inv-line span:first-child { opacity: 0.85; }
        .inv-line span:last-child { font-weight: 700; font-variant-numeric: tabular-nums; }
        .inv-status-pill { display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 999px; background: rgba(255,255,255,0.18); font-size: 0.82rem; font-weight: 600; margin-top: 16px; }
        .inv-status-pill::before { content: ''; width: 7px; height: 7px; border-radius: 50%; background: currentColor; }
        .completed-item { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 11px 0; border-bottom: 1px solid var(--border); }
        .completed-item:last-child { border-bottom: none; }
        .ci-name { font-weight: 600; font-size: 0.9rem; }
        .ci-sub { font-size: 0.77rem; color: var(--muted); margin-top: 2px; }
        @media (max-width: 900px) { .invoice-layout { grid-template-columns: 1fr; } .inv-summary { position: static; } }

        /* Mobile */
        .menu-btn { display: none; }
        @media (max-width: 1024px) {
            .stat-grid { grid-template-columns: repeat(2, 1fr); }
            .dash-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 860px) {
            .sidebar { position: fixed; z-index: 60; left: 0; top: 0; transform: translateX(-100%); transition: transform .25s ease; box-shadow: 0 0 60px rgba(0,0,0,0.4); }
            .app.nav-open .sidebar { transform: translateX(0); }
            .nav-scrim { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 55; }
            .app.nav-open .nav-scrim { display: block; }
            .menu-btn { display: grid; }
            .form-grid { grid-template-columns: 1fr; }
            .quick-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 560px) {
            .content { padding: 18px; }
            .topbar { padding: 12px 16px; }
            .stat-grid { grid-template-columns: 1fr; }
            .quick-grid { grid-template-columns: 1fr; }
            .clock { display: none; }
            .user-meta { display: none; }
        }
    </style>
</head>
<body>
<div class="app" id="app">
    <div class="nav-scrim" onclick="document.getElementById('app').classList.remove('nav-open')"></div>
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-logo">PB</div>
            <div>
                <div class="brand-name">Prime Byte</div>
                <div class="brand-sub">Software Solution</div>
            </div>
        </div>
        <nav class="nav">
            @php $u = auth()->user(); @endphp
            @if($u->canView('dashboard'))
                <div class="nav-label">Main</div>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9"/><rect x="14" y="3" width="7" height="5"/><rect x="14" y="12" width="7" height="9"/><rect x="3" y="16" width="7" height="5"/></svg>
                    Dashboard
                </a>
            @endif
            @if($u->canView('clients') || $u->canView('projects') || $u->canView('team') || $u->canView('banks') || $u->canView('expenses') || $u->canView('invoices') || $u->canView('fully_paid'))
                <div class="nav-label">Management</div>
            @endif
            @if($u->canView('clients'))
                <a href="{{ route('clients.index') }}" class="{{ request()->routeIs('clients.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Clients
                </a>
            @endif
            @if($u->canView('projects'))
                <a href="{{ route('projects.index') }}" class="{{ request()->routeIs('projects.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                    Projects
                </a>
            @endif
            @if($u->canView('team'))
                <a href="{{ route('team.members.index') }}" class="{{ request()->routeIs('team.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Team &amp; Projects
                </a>
            @endif
            @if($u->canView('banks'))
                <a href="{{ route('banks.index') }}" class="{{ request()->routeIs('banks.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="22" x2="21" y2="22"/><line x1="6" y1="18" x2="6" y2="11"/><line x1="10" y1="18" x2="10" y2="11"/><line x1="14" y1="18" x2="14" y2="11"/><line x1="18" y1="18" x2="18" y2="11"/><polygon points="12 2 20 7 4 7"/></svg>
                    Banks
                </a>
            @endif
            @if($u->canView('expenses'))
                <a href="{{ route('expenses.index') }}" class="{{ request()->routeIs('expenses.*') || request()->routeIs('expense-heads.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                    Expenses
                </a>
            @endif
            @if($u->canView('invoices'))
                <a href="{{ route('invoices.index') }}" class="{{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/></svg>
                    Invoices
                </a>
            @endif
            @if($u->canView('fully_paid'))
                <a href="{{ route('fully-paid.index') }}" class="{{ request()->routeIs('fully-paid.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    Fully Paid
                </a>
            @endif
            @if($u->canView('customers') || $u->canView('reviews') || $u->canView('contacts') || $u->canView('code_requests') || $u->canView('users') || $u->canView('history'))
                <div class="nav-admin">
                    <div class="nav-label">Administration</div>
                    @if($u->canView('customers'))
                    <a href="{{ route('customers.index') }}" class="{{ request()->routeIs('customers.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-3-3.87"/><path d="M4 21v-2a4 4 0 0 1 3-3.87"/><circle cx="12" cy="7" r="4"/><path d="M12 15a6 6 0 0 0-6 6"/><path d="M18 21a6 6 0 0 0-6-6"/></svg>
                        Portal Customers
                    </a>
                    @endif
                    @if($u->canView('reviews'))
                    <a href="{{ route('reviews.index') }}" class="{{ request()->routeIs('reviews.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15 8.5 22 9.3 17 14 18.2 21 12 17.5 5.8 21 7 14 2 9.3 9 8.5 12 2"/></svg>
                        Reviews
                    </a>
                    @endif
                    @if($u->canView('contacts'))
                    <a href="{{ route('contacts.index') }}" class="{{ request()->routeIs('contacts.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        Messages
                    </a>
                    @endif
                    @if($u->canView('code_requests'))
                    <a href="{{ route('code-requests.index') }}" class="{{ request()->routeIs('code-requests.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                        Code Requests
                    </a>
                    @endif
                    @if($u->canView('users'))
                    <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        Users
                    </a>
                    <a href="{{ route('roles.index') }}" class="{{ request()->routeIs('roles.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l7 4v6c0 5-3.5 8-7 10-3.5-2-7-5-7-10V6z"/></svg>
                        Roles
                    </a>
                    @endif
                    @if($u->canView('history'))
                    <a href="{{ route('history.index') }}" class="{{ request()->routeIs('history.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        History
                    </a>
                    @endif
                </div>
            @endif
        </nav>
        <div class="sidebar-foot">v1.0 · Accounting Suite</div>
    </aside>

    <main class="main">
        <header class="topbar">
            <div style="display:flex; align-items:center; gap:12px;">
                <button class="icon-btn menu-btn" onclick="document.getElementById('app').classList.toggle('nav-open')" aria-label="Menu">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>
                <span class="topbar-title">@yield('title', 'Dashboard')</span>
            </div>
            @php
                $notifIconMap = function ($n) {
                    $key = $n->icon ?: $n->type;
                    return match ($key) {
                        'user', 'customer' => ['#4f46e5', '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>'],
                        'star', 'review'   => ['#f59e0b', '<polygon points="12 2 15 8.5 22 9.3 17 14 18.2 21 12 17.5 5.8 21 7 14 2 9.3 9 8.5 12 2"/>'],
                        'mail', 'message'  => ['#0ea5e9', '<path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/><polyline points="22,6 12,13 2,6"/>'],
                        default            => ['#8b5cf6', '<path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>'],
                    };
                };
                try {
                    $recentNotifs = \App\Models\AdminNotification::latest()->take(8)->get();
                    $unreadNotifs = \App\Models\AdminNotification::unread()->count();
                    $maxNotifId = (int) ($recentNotifs->max('id') ?? 0);
                } catch (\Throwable $e) {
                    $recentNotifs = collect();
                    $unreadNotifs = 0;
                    $maxNotifId = 0;
                }
            @endphp
            <div class="topbar-right">
                <span class="clock" id="clock">00:00:00</span>
                <button class="icon-btn theme-toggle" onclick="toggleTheme()" aria-label="Toggle theme" title="Toggle theme">
                    <svg class="moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                    <svg class="sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                </button>

                {{-- Notification bell --}}
                <div class="hdr-menu" data-menu>
                    <button class="icon-btn bell-btn" data-menu-toggle aria-label="Notifications" title="Notifications">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                        @if($unreadNotifs > 0)<span class="bell-badge">{{ $unreadNotifs > 9 ? '9+' : $unreadNotifs }}</span>@endif
                    </button>
                    <div class="dropdown">
                        <div class="dropdown-head">
                            <h4>Notifications</h4>
                            @if($unreadNotifs > 0)
                            <form method="POST" action="{{ route('notifications.readAll') }}">@csrf
                                <button class="mark-read" type="submit">Mark all read</button>
                            </form>
                            @endif
                        </div>
                        <div class="dd-scroll">
                            @forelse($recentNotifs as $n)
                                @php [$accent, $path] = $notifIconMap($n); @endphp
                                <a href="{{ route('notifications.open', $n->id) }}" class="dd-notif {{ $n->is_read ? '' : 'unread' }}">
                                    <span class="dd-ic" style="background:{{ $accent }}1a; color:{{ $accent }}">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $path !!}</svg>
                                    </span>
                                    <span class="dd-txt">
                                        <span class="t">{{ $n->title }}</span>
                                        @if($n->body)<span class="b">{{ $n->body }}</span>@endif
                                        <span class="tm">{{ $n->created_at->diffForHumans() }}</span>
                                    </span>
                                </a>
                            @empty
                                <div class="dd-empty">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                                    <div>No notifications yet.</div>
                                </div>
                            @endforelse
                        </div>
                        <div class="dropdown-foot">
                            <a href="{{ route('notifications.index') }}">View all notifications</a>
                        </div>
                    </div>
                </div>

                {{-- Profile --}}
                <div class="hdr-menu" data-menu>
                    <button class="profile-btn" data-menu-toggle aria-label="Profile menu">
                        <div class="avatar">{{ strtoupper(mb_substr(auth()->user()->name ?? 'U', 0, 1)) }}</div>
                        <div class="user-meta">
                            <div class="user-name">{{ auth()->user()->name }}</div>
                            <div class="user-role">{{ auth()->user()->isSuperAdmin() ? 'Super Admin' : (auth()->user()->role->name ?? 'No role') }}</div>
                        </div>
                        <svg class="caret" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="dropdown" style="width:280px;">
                        <div class="pd-head">
                            <div class="pd-avatar">{{ strtoupper(mb_substr(auth()->user()->name ?? 'U', 0, 1)) }}</div>
                            <div style="min-width:0;">
                                <div class="pd-name">{{ auth()->user()->name }}</div>
                                <div class="pd-role">{{ auth()->user()->isSuperAdmin() ? 'Super Admin' : (auth()->user()->role->name ?? 'No role') }}</div>
                                <div class="pd-email">{{ auth()->user()->email }}</div>
                            </div>
                        </div>
                        <div class="pd-links">
                            <a class="pd-link" href="{{ route('settings.index') }}#profile">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                My Profile
                            </a>
                            <a class="pd-link" href="{{ route('settings.index') }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                                Settings
                            </a>
                            <div class="pd-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">@csrf
                                <button class="pd-link danger" type="submit">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <section class="content">
            @yield('content')
        </section>

        <footer class="site-footer">
            <div>&copy; {{ date('Y') }} Prime Byte Software Solution. All rights reserved.</div>
            <div class="foot-links">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('clients.index') }}">Clients</a>
                <a href="{{ route('projects.index') }}">Projects</a>
                <a href="{{ route('invoices.index') }}">Invoices</a>
            </div>
            <div>Built for growing businesses</div>
        </footer>
    </main>
</div>

<div class="toast-wrap" id="toastWrap">
    @if (session('status'))
        <div class="toast toast-success" data-toast>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <span>{{ session('status') }}</span>
            <button class="toast-close" onclick="this.parentElement.remove()" aria-label="Dismiss">&times;</button>
        </div>
    @endif
    @if ($errors->any())
        <div class="toast toast-danger" data-toast>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span>{{ $errors->first() }}</span>
            <button class="toast-close" onclick="this.parentElement.remove()" aria-label="Dismiss">&times;</button>
        </div>
    @endif
</div>

<script>
    // Auto-dismiss toasts.
    document.querySelectorAll('[data-toast]').forEach(function (t) {
        setTimeout(function () {
            t.style.opacity = '0';
            t.style.transform = 'translateX(120%)';
            setTimeout(function () { t.remove(); }, 350);
        }, 4000);
    });
    function toggleTheme() {
        var cur = document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
        var next = cur === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', next);
        try { localStorage.setItem('pb-theme', next); } catch (e) {}
    }
    (function () {
        var el = document.getElementById('clock');
        function tick() { if (el) el.textContent = new Date().toLocaleTimeString('en-US', { hour12: false }); }
        tick(); setInterval(tick, 1000);
    })();
    // Header dropdown menus (notifications + profile) — click to toggle, click-outside/Esc to close.
    (function () {
        var menus = document.querySelectorAll('[data-menu]');
        function closeAll(except) {
            menus.forEach(function (m) { if (m !== except) m.classList.remove('open'); });
        }
        menus.forEach(function (menu) {
            var toggle = menu.querySelector('[data-menu-toggle]');
            if (!toggle) return;
            toggle.addEventListener('click', function (e) {
                e.stopPropagation();
                var isOpen = menu.classList.contains('open');
                closeAll(menu);
                menu.classList.toggle('open', !isOpen);
            });
            menu.querySelector('.dropdown').addEventListener('click', function (e) { e.stopPropagation(); });
        });
        document.addEventListener('click', function () { closeAll(null); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeAll(null); });
    })();
    // Live notification popups — poll the feed and toast anything new (code requests, reviews, messages…).
    (function () {
        var feedUrl = @json(route('notifications.feed'));
        var lastSeen = {{ $maxNotifId ?? 0 }};
        var wrap = document.getElementById('toastWrap');
        var bellBtn = document.querySelector('.bell-btn');

        function iconFor(key) {
            switch (key) {
                case 'user': case 'customer': return ['#4f46e5', '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>'];
                case 'star': case 'review': return ['#f59e0b', '<polygon points="12 2 15 8.5 22 9.3 17 14 18.2 21 12 17.5 5.8 21 7 14 2 9.3 9 8.5 12 2"/>'];
                case 'mail': case 'message': return ['#0ea5e9', '<path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/><polyline points="22,6 12,13 2,6"/>'];
                default: return ['#8b5cf6', '<path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>'];
            }
        }
        function escapeHtml(s) { var d = document.createElement('div'); d.textContent = (s == null ? '' : s); return d.innerHTML; }

        function showToast(item) {
            if (!wrap) return;
            var ic = iconFor(item.icon);
            var a = document.createElement('a');
            a.href = item.url;
            a.className = 'toast toast-notif';
            a.setAttribute('data-toast', '');
            a.innerHTML =
                '<span class="tn-ic" style="background:' + ic[0] + '1a; color:' + ic[0] + '"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' + ic[1] + '</svg></span>' +
                '<span class="tn-main"><span class="tn-t">' + escapeHtml(item.title) + '</span>' +
                (item.body ? '<span class="tn-b">' + escapeHtml(item.body) + '</span>' : '') + '</span>' +
                '<button class="toast-close" aria-label="Dismiss">&times;</button>';
            a.querySelector('.toast-close').addEventListener('click', function (e) { e.preventDefault(); e.stopPropagation(); a.remove(); });
            wrap.appendChild(a);
            setTimeout(function () {
                a.style.opacity = '0'; a.style.transform = 'translateX(120%)';
                setTimeout(function () { a.remove(); }, 350);
            }, 9000);
        }

        function updateBadge(count) {
            if (!bellBtn) return;
            var badge = bellBtn.querySelector('.bell-badge');
            if (count > 0) {
                if (!badge) { badge = document.createElement('span'); badge.className = 'bell-badge'; bellBtn.appendChild(badge); }
                badge.textContent = count > 9 ? '9+' : count;
            } else if (badge) {
                badge.remove();
            }
        }

        function poll() {
            if (document.hidden) return;
            fetch(feedUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
                .then(function (r) { return r.ok ? r.json() : null; })
                .then(function (data) {
                    if (!data) return;
                    updateBadge(data.unread);
                    var items = data.items || [];
                    items.filter(function (it) { return it.id > lastSeen; })
                        .sort(function (a, b) { return a.id - b.id; })
                        .forEach(showToast);
                    if (items.length) {
                        lastSeen = Math.max.apply(null, items.map(function (it) { return it.id; }).concat([lastSeen]));
                    }
                })
                .catch(function () {});
        }
        setInterval(poll, 25000);
    })();
    // Combined client-side search + status filtering per table.
    // input[data-search="#tbl"] and button[data-filter="#tbl" data-value="Paid"]
    (function () {
        var selectors = new Set();
        document.querySelectorAll('[data-search]').forEach(function (e) { selectors.add(e.getAttribute('data-search')); });
        document.querySelectorAll('[data-filter]').forEach(function (e) { selectors.add(e.getAttribute('data-filter')); });

        selectors.forEach(function (sel) {
            var table = document.querySelector(sel);
            if (!table) return;
            var state = { q: '', status: 'all' };
            var noRes = table.querySelector('.no-results');

            function apply() {
                var shown = 0;
                table.querySelectorAll('tbody tr').forEach(function (row) {
                    if (row.classList.contains('no-results') || row.hasAttribute('data-empty')) return;
                    var matchQ = row.textContent.toLowerCase().indexOf(state.q) !== -1;
                    var rowStatus = (row.getAttribute('data-status') || '').toLowerCase();
                    var matchS = state.status === 'all' || rowStatus === state.status;
                    var show = matchQ && matchS;
                    row.style.display = show ? '' : 'none';
                    if (show) shown++;
                });
                if (noRes) noRes.style.display = shown === 0 ? '' : 'none';
            }

            document.querySelectorAll('[data-search="' + sel + '"]').forEach(function (inp) {
                inp.addEventListener('input', function () { state.q = this.value.trim().toLowerCase(); apply(); });
            });
            document.querySelectorAll('[data-filter="' + sel + '"]').forEach(function (chip) {
                chip.addEventListener('click', function () {
                    state.status = (this.getAttribute('data-value') || 'all').toLowerCase();
                    document.querySelectorAll('[data-filter="' + sel + '"]').forEach(function (c) { c.classList.remove('active'); });
                    this.classList.add('active');
                    apply();
                });
            });
        });
    })();
</script>
@yield('scripts')
</body>
</html>
