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
        .nav { padding: 8px 12px; flex: 1; overflow-y: auto; }
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
        .avatar { width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), #8b5cf6); color: #fff; display: grid; place-items: center; font-weight: 700; font-size: 0.85rem; }

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
            <div class="nav-label">Main</div>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9"/><rect x="14" y="3" width="7" height="5"/><rect x="14" y="12" width="7" height="9"/><rect x="3" y="16" width="7" height="5"/></svg>
                Dashboard
            </a>
            <div class="nav-label">Management</div>
            <a href="{{ route('clients.index') }}" class="{{ request()->routeIs('clients.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Clients
            </a>
            <a href="{{ route('projects.index') }}" class="{{ request()->routeIs('projects.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                Projects
            </a>
            <a href="{{ route('banks.index') }}" class="{{ request()->routeIs('banks.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="22" x2="21" y2="22"/><line x1="6" y1="18" x2="6" y2="11"/><line x1="10" y1="18" x2="10" y2="11"/><line x1="14" y1="18" x2="14" y2="11"/><line x1="18" y1="18" x2="18" y2="11"/><polygon points="12 2 20 7 4 7"/></svg>
                Banks
            </a>
            <a href="{{ route('invoices.index') }}" class="{{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/></svg>
                Invoices
            </a>
            <a href="{{ route('fully-paid.index') }}" class="{{ request()->routeIs('fully-paid.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                Fully Paid
            </a>
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
            <div class="topbar-right">
                <span class="clock" id="clock">00:00:00</span>
                <button class="icon-btn theme-toggle" onclick="toggleTheme()" aria-label="Toggle theme" title="Toggle theme">
                    <svg class="moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                    <svg class="sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                </button>
                <div class="avatar">PB</div>
                <form method="POST" action="{{ route('logout') }}">@csrf
                    <button class="btn btn-ghost btn-sm" type="submit">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        Logout
                    </button>
                </form>
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
