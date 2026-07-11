<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Client Portal') · Prime Byte</title>
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
            --primary: #6366f1; --primary-2: #8b5cf6; --accent: #06b6d4; --radius: 16px;
            --grad: linear-gradient(135deg, #6366f1, #8b5cf6 55%, #a855f7);
            --grad-2: linear-gradient(135deg, #06b6d4, #6366f1);
        }
        :root[data-theme="light"] {
            --bg: #ffffff; --bg-alt: #f5f7fe; --surface: #ffffff; --surface-2: #f8fafc;
            --text: #0b1220; --text-soft: #475569; --muted: #64748b; --border: #e7ebf5;
            --primary-soft: #eef0fe; --header-bg: rgba(255,255,255,0.82); --shadow: 0 20px 50px rgba(30,27,75,0.1);
            --glow-1: rgba(99,102,241,0.14); --glow-2: rgba(139,92,246,0.12); --glow-3: rgba(6,182,212,0.10);
            --footer-bg: #0b1120; --footer-text: #93a2ba;
            --success: #16a34a; --success-soft: #e7f6ee; --warning: #d97706; --warning-soft: #fdf1e2; --danger: #dc2626; --danger-soft: #fdecec;
        }
        :root[data-theme="dark"] {
            --bg: #080b16; --bg-alt: #0c1120; --surface: #111827; --surface-2: #0f1526;
            --text: #eef2fb; --text-soft: #b9c4d8; --muted: #8493ab; --border: #1e293b;
            --primary-soft: rgba(99,102,241,0.16); --header-bg: rgba(8,11,22,0.82); --shadow: 0 24px 60px rgba(0,0,0,0.5);
            --glow-1: rgba(99,102,241,0.22); --glow-2: rgba(139,92,246,0.18); --glow-3: rgba(6,182,212,0.16);
            --footer-bg: #05070f; --footer-text: #8494ac;
            --success: #34d399; --success-soft: rgba(52,211,153,0.14); --warning: #fbbf24; --warning-soft: rgba(251,191,36,0.14); --danger: #f87171; --danger-soft: rgba(248,113,113,0.14);
        }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { margin: 0; font-family: var(--font); color: var(--text); background: var(--bg); line-height: 1.65; -webkit-font-smoothing: antialiased; overflow-x: hidden; }
        a { color: inherit; text-decoration: none; }
        h1, h2, h3, h4 { margin: 0; font-weight: 800; letter-spacing: -0.02em; }
        img { max-width: 100%; }
        .wrap { max-width: 1180px; margin: 0 auto; padding: 0 24px; }
        .gtext { background: var(--grad); -webkit-background-clip: text; background-clip: text; color: transparent; }

        /* Header */
        .site-header { position: sticky; top: 0; z-index: 50; background: var(--header-bg); backdrop-filter: blur(14px); border-bottom: 1px solid var(--border); }
        .header-inner { display: flex; align-items: center; gap: 20px; height: 74px; }
        .logo { display: flex; align-items: center; gap: 12px; flex-shrink: 0; }
        .logo-mark { width: 42px; height: 42px; border-radius: 12px; background: var(--grad); color: #fff; display: grid; place-items: center; font-weight: 800; font-size: 1.05rem; box-shadow: 0 8px 20px var(--glow-1); flex-shrink: 0; }
        .logo-mark svg { width: 24px; height: 24px; }
        .logo-text { display: flex; flex-direction: column; line-height: 1.15; }
        .logo-name { font-weight: 800; font-size: 1.06rem; }
        .logo-sub { font-size: 0.7rem; color: var(--muted); letter-spacing: 0.04em; }
        .nav-links { display: flex; align-items: center; gap: 4px; margin: 0 auto; }
        .nav-links a { padding: 9px 15px; border-radius: 10px; font-size: 0.92rem; font-weight: 600; color: var(--text-soft); transition: background .15s, color .15s; }
        .nav-links a:hover, .nav-links a.active { background: var(--primary-soft); color: var(--primary); }
        .header-cta { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
        .icon-btn { width: 42px; height: 42px; border-radius: 11px; border: 1px solid var(--border); background: var(--surface); color: var(--text-soft); cursor: pointer; display: grid; place-items: center; transition: background .15s, border-color .15s; }
        .icon-btn:hover { background: var(--surface-2); }
        .icon-btn svg { width: 19px; height: 19px; }
        .theme-toggle .sun { display: none; }
        :root[data-theme="dark"] .theme-toggle .sun { display: block; }
        :root[data-theme="dark"] .theme-toggle .moon { display: none; }
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 11px 20px; border-radius: 11px; font-weight: 700; font-size: 0.9rem; cursor: pointer; border: 1px solid transparent; transition: transform .12s, filter .15s, background .15s; white-space: nowrap; }
        .btn:active { transform: translateY(1px); }
        .btn svg { width: 16px; height: 16px; }
        .btn-primary { background: var(--grad); color: #fff; box-shadow: 0 10px 24px var(--glow-1); }
        .btn-primary:hover { filter: brightness(1.08); }
        .btn-ghost { background: var(--surface); color: var(--text); border-color: var(--border); }
        .btn-ghost:hover { background: var(--surface-2); }
        .btn-light { background: #fff; color: #4338ca; }
        .btn-outline-light { background: rgba(255,255,255,0.14); color: #fff; border-color: rgba(255,255,255,0.35); }
        .cust-chip { display: flex; align-items: center; gap: 9px; }
        .cust-av { width: 38px; height: 38px; border-radius: 50%; background: var(--grad); color: #fff; display: grid; place-items: center; font-weight: 700; font-size: 0.82rem; }
        .cust-name { font-size: 0.86rem; font-weight: 700; line-height: 1.2; }
        .cust-sub { font-size: 0.72rem; color: var(--muted); }
        .menu-toggle { display: none; }

        main { min-height: 60vh; }

        /* Eyebrow + sections */
        .eyebrow { display: inline-flex; align-items: center; gap: 8px; padding: 6px 14px; border-radius: 999px; background: var(--primary-soft); color: var(--primary); font-size: 0.8rem; font-weight: 700; margin-bottom: 16px; }
        .section { padding: 84px 0; position: relative; }
        .section.alt { background: var(--bg-alt); }
        .section-head { text-align: center; max-width: 680px; margin: 0 auto 52px; }
        .section-head h2 { font-size: clamp(1.7rem, 3.2vw, 2.5rem); margin-bottom: 14px; }
        .section-head p { color: var(--muted); font-size: 1.05rem; }

        /* Hero carousel */
        .hero-wrap { padding-top: 26px; position: relative; }
        .hero-wrap::before { content:''; position:absolute; top:-40px; left:10%; width:420px; height:420px; border-radius:50%; background:var(--glow-1); filter:blur(80px); z-index:-1; }
        .hero-wrap::after { content:''; position:absolute; top:60px; right:6%; width:360px; height:360px; border-radius:50%; background:var(--glow-3); filter:blur(80px); z-index:-1; }
        .carousel { position: relative; overflow: hidden; border-radius: 24px; box-shadow: var(--shadow); }
        .c-track { display: flex; transition: transform .6s cubic-bezier(.6,.05,.3,1); }
        .c-slide { min-width: 100%; }
        .hero-slide { display: grid; grid-template-columns: 1.05fr 0.95fr; align-items: center; gap: 28px; min-height: 470px; padding: 56px; color: #fff; position: relative; overflow: hidden; }
        .hero-slide::after { content:''; position:absolute; inset:0; background: radial-gradient(600px 300px at 85% 20%, rgba(255,255,255,0.16), transparent 60%); pointer-events:none; }
        .hero-slide .hs-inner { max-width: 560px; position: relative; z-index: 1; }
        .hero-slide .eyebrow { background: rgba(255,255,255,0.18); color: #fff; }
        .hero-slide h2 { font-size: clamp(1.9rem, 3.6vw, 3rem); margin-bottom: 16px; line-height: 1.1; }
        .hero-slide p { font-size: 1.08rem; opacity: 0.95; margin-bottom: 26px; }
        .hs-art { position: relative; z-index: 1; display: grid; place-items: center; }
        .hs-art svg { width: 100%; max-width: 430px; height: auto; filter: drop-shadow(0 20px 40px rgba(0,0,0,0.25)); }
        .hs1 { background: linear-gradient(125deg, #4338ca, #6d28d9 55%, #9333ea); }
        .hs2 { background: linear-gradient(125deg, #0e7490, #0891b2 45%, #4f46e5); }
        .hs3 { background: linear-gradient(125deg, #9d174d, #be185d 50%, #7c3aed); }
        .c-arrow { position: absolute; top: 50%; transform: translateY(-50%); width: 46px; height: 46px; border-radius: 50%; border: none; background: rgba(255,255,255,0.9); color: #0f172a; cursor: pointer; display: grid; place-items: center; box-shadow: 0 6px 18px rgba(0,0,0,0.2); z-index: 3; }
        .c-arrow svg { width: 20px; height: 20px; }
        .c-arrow:hover { background: #fff; }
        .c-prev { left: 16px; } .c-next { right: 16px; }
        .c-dots { position: absolute; bottom: 18px; left: 0; right: 0; display: flex; gap: 8px; justify-content: center; z-index: 3; }
        .c-dot { width: 9px; height: 9px; border-radius: 50%; border: none; background: rgba(255,255,255,0.5); cursor: pointer; padding: 0; transition: width .2s; }
        .c-dot.active { background: #fff; width: 26px; border-radius: 6px; }

        /* Trust bar */
        .trust { display: flex; align-items: center; justify-content: center; gap: 44px; flex-wrap: wrap; padding: 26px 0; }
        .trust .t { text-align: center; }
        .trust .t .n { font-size: 2rem; font-weight: 800; }
        .trust .t .n .gtext { font-weight: 800; }
        .trust .t .l { font-size: 0.86rem; color: var(--muted); }

        /* Track box */
        .track-card { background: var(--surface); border: 1px solid var(--border); border-radius: 20px; padding: 30px; box-shadow: var(--shadow); max-width: 680px; margin: 0 auto; text-align: center; }
        .track-card h3 { font-size: 1.3rem; margin-bottom: 6px; }
        .track-card .muted { color: var(--muted); margin-bottom: 18px; }
        .track-field { display: flex; gap: 10px; }
        .track-field input { flex: 1; padding: 14px 16px; border-radius: 12px; border: 1px solid var(--border); font-family: inherit; font-size: 1rem; outline: none; background: var(--surface); color: var(--text); text-transform: uppercase; }
        .track-field input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-soft); }

        /* Cards */
        .cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
        .m-card { background: var(--surface); border: 1px solid var(--border); border-radius: 18px; padding: 30px; transition: transform .2s, box-shadow .2s, border-color .2s; position: relative; overflow: hidden; }
        .m-card:hover { transform: translateY(-6px); box-shadow: var(--shadow); border-color: transparent; }
        .m-card .ic { width: 54px; height: 54px; border-radius: 15px; display: grid; place-items: center; color: #fff; margin-bottom: 18px; font-weight: 800; font-size: 1.2rem; }
        .m-card .ic svg { width: 26px; height: 26px; }
        .m-card h3 { font-size: 1.15rem; margin-bottom: 8px; }
        .m-card p { color: var(--muted); font-size: 0.94rem; }

        /* Split feature */
        .split { display: grid; grid-template-columns: 1fr 1fr; gap: 48px; align-items: center; }
        .split-art { border-radius: 20px; overflow: hidden; box-shadow: var(--shadow); }
        .split-art svg { display: block; width: 100%; height: auto; }
        .flist { display: grid; gap: 16px; margin-top: 24px; }
        .flist .fi { display: flex; gap: 13px; align-items: flex-start; }
        .flist .fi .ck { width: 30px; height: 30px; border-radius: 9px; background: var(--primary-soft); color: var(--primary); display: grid; place-items: center; flex-shrink: 0; }
        .flist .fi .ck svg { width: 16px; height: 16px; }
        .flist .fi h4 { font-size: 1rem; margin-bottom: 2px; }
        .flist .fi p { font-size: 0.9rem; color: var(--muted); margin: 0; }

        /* How it works */
        .steps { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
        .step { text-align: center; padding: 10px; }
        .step .num { width: 60px; height: 60px; margin: 0 auto 16px; border-radius: 18px; background: var(--grad); color: #fff; display: grid; place-items: center; font-size: 1.5rem; font-weight: 800; box-shadow: 0 12px 26px var(--glow-1); }
        .step h3 { font-size: 1.12rem; margin-bottom: 8px; }
        .step p { color: var(--muted); font-size: 0.92rem; }

        /* Testimonials */
        .tcard { background: var(--surface); border: 1px solid var(--border); border-radius: 20px; padding: 40px; text-align: center; max-width: 760px; margin: 0 auto; }
        .tcard .stars { color: #f59e0b; letter-spacing: 3px; margin-bottom: 16px; font-size: 1.1rem; }
        .tcard p { font-size: 1.2rem; line-height: 1.7; color: var(--text); margin-bottom: 20px; font-weight: 500; }
        .tcard .who { display: flex; align-items: center; justify-content: center; gap: 12px; }
        .tcard .who .av { width: 46px; height: 46px; border-radius: 50%; background: var(--grad); color: #fff; display: grid; place-items: center; font-weight: 700; }
        .tcard .who .nm { text-align: left; }
        .tcard .who .nm b { display: block; }
        .tcard .who .nm span { color: var(--muted); font-size: 0.84rem; }

        /* CTA */
        .cta-band { background: var(--grad); color: #fff; border-radius: 26px; padding: 56px; text-align: center; position: relative; overflow: hidden; box-shadow: 0 30px 60px var(--glow-2); }
        .cta-band::before { content:''; position:absolute; top:-60px; right:-40px; width:260px; height:260px; border-radius:50%; background:rgba(255,255,255,0.12); }
        .cta-band h2 { font-size: clamp(1.6rem, 3vw, 2.3rem); margin-bottom: 12px; position: relative; }
        .cta-band p { opacity: 0.92; margin-bottom: 26px; position: relative; }
        .cta-actions { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; position: relative; }

        /* Prose / about */
        .prose { max-width: 780px; margin: 0 auto; }
        .prose p { color: var(--muted); font-size: 1.05rem; margin-bottom: 16px; }
        .value-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-top: 34px; }
        .value-item { display: flex; gap: 14px; align-items: flex-start; background: var(--surface); border: 1px solid var(--border); border-radius: 14px; padding: 20px; }
        .value-item .vic { width: 44px; height: 44px; border-radius: 12px; background: var(--primary-soft); color: var(--primary); display: grid; place-items: center; flex-shrink: 0; }
        .value-item .vic svg { width: 21px; height: 21px; }
        .value-item h4 { font-size: 1.02rem; margin-bottom: 3px; }
        .value-item p { font-size: 0.9rem; color: var(--muted); margin: 0; }

        /* Contact */
        .contact-grid { display: grid; grid-template-columns: 1fr 1.25fr; gap: 40px; }
        .contact-info { display: grid; gap: 18px; align-content: start; }
        .contact-info .ci { display: flex; gap: 14px; align-items: flex-start; }
        .contact-info .ci .cic { width: 48px; height: 48px; border-radius: 13px; background: var(--grad); color: #fff; display: grid; place-items: center; flex-shrink: 0; }
        .contact-info .ci .cic svg { width: 22px; height: 22px; }
        .contact-info .ci h4 { font-size: 1rem; margin-bottom: 2px; }

        /* Forms */
        .form-card { background: var(--surface); border: 1px solid var(--border); border-radius: 20px; padding: 32px; box-shadow: var(--shadow); }
        .fld { display: grid; gap: 7px; margin-bottom: 18px; }
        .fld label { font-size: 0.86rem; font-weight: 600; color: var(--text-soft); }
        .fld input, .fld textarea, .input, .select { width: 100%; padding: 12px 14px; border-radius: 11px; border: 1px solid var(--border); font-family: inherit; font-size: 0.95rem; outline: none; background: var(--surface); color: var(--text); transition: border-color .15s, box-shadow .15s; }
        .fld input:focus, .fld textarea:focus, .input:focus, .select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-soft); }
        .req { color: var(--danger); font-weight: 700; }
        .form-legend { font-size: 0.8rem; color: var(--muted); margin-top: 6px; }
        .hint { font-size: 0.78rem; color: var(--muted); }
        .select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 13px center; padding-right: 34px; }
        .phone-field { display: flex; gap: 8px; }
        .phone-field .phone-code { width: 132px; flex-shrink: 0; }
        .phone-field .phone-number { flex: 1; }
        .pw-wrap { position: relative; }
        .pw-wrap input { padding-right: 44px; }
        .pw-toggle { position: absolute; right: 8px; top: 50%; transform: translateY(-50%); width: 34px; height: 34px; border: none; background: none; color: var(--muted); cursor: pointer; display: grid; place-items: center; border-radius: 8px; }
        .pw-toggle:hover { background: var(--surface-2); color: var(--text); }
        .pw-toggle svg { width: 18px; height: 18px; }
        .pw-toggle .eye-off { display: none; }
        .pw-toggle.show .eye { display: none; }
        .pw-toggle.show .eye-off { display: block; }

        /* Footer */
        .site-footer { background: var(--footer-bg); color: var(--footer-text); margin-top: 90px; }
        .footer-grid { display: grid; grid-template-columns: 1.7fr 1fr 1fr 1.4fr; gap: 34px; padding: 60px 0 36px; }
        .site-footer h4 { color: #fff; font-size: 0.98rem; margin-bottom: 16px; }
        .site-footer .fdesc { font-size: 0.9rem; line-height: 1.75; max-width: 300px; }
        .flinks { display: grid; gap: 10px; }
        .flinks a { color: var(--footer-text); font-size: 0.9rem; transition: color .15s; }
        .flinks a:hover { color: #fff; }
        .fcontact { font-size: 0.9rem; display: grid; gap: 10px; }
        .footer-logo { display: flex; align-items: center; gap: 11px; margin-bottom: 16px; }
        .fsocial { display: flex; gap: 10px; margin-top: 16px; }
        .fsocial a { width: 38px; height: 38px; border-radius: 10px; background: rgba(255,255,255,0.08); display: grid; place-items: center; color: #cbd5e1; }
        .fsocial a:hover { background: var(--primary); color: #fff; }
        .fsocial svg { width: 17px; height: 17px; }
        .footer-bottom { border-top: 1px solid rgba(255,255,255,0.08); padding: 20px 0; display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; font-size: 0.82rem; }

        /* Rating summary + review marquee */
        .rating-summary { display: flex; align-items: center; justify-content: center; gap: 18px; flex-wrap: wrap; margin-bottom: 40px; }
        .rating-summary .big { font-size: 2.6rem; font-weight: 800; line-height: 1; }
        .rating-summary .rs-stars { color: #f59e0b; font-size: 1.3rem; letter-spacing: 2px; }
        .rating-summary .rs-meta { color: var(--muted); font-size: 0.9rem; }
        .rev-marquee { overflow: hidden; padding: 6px 0; -webkit-mask-image: linear-gradient(90deg, transparent, #000 5%, #000 95%, transparent); mask-image: linear-gradient(90deg, transparent, #000 5%, #000 95%, transparent); }
        .rev-track { display: flex; gap: 22px; width: max-content; animation: revscroll 48s linear infinite; }
        .rev-marquee:hover .rev-track { animation-play-state: paused; }
        @keyframes revscroll { from { transform: translateX(0); } to { transform: translateX(-50%); } }
        .rev-card { width: 320px; flex-shrink: 0; background: var(--surface); border: 1px solid var(--border); border-radius: 18px; padding: 26px; box-shadow: 0 6px 22px rgba(16,24,40,0.05); }
        .rev-card .rc-stars { color: #f59e0b; letter-spacing: 2px; margin-bottom: 12px; }
        .rev-card .rc-text { color: var(--text-soft); font-size: 0.96rem; line-height: 1.65; margin-bottom: 18px; }
        .rev-card .rc-who { display: flex; align-items: center; gap: 12px; }
        .rev-card .rc-av { width: 46px; height: 46px; border-radius: 50%; background: var(--grad) center/cover no-repeat; flex-shrink: 0; }
        .rev-card .rc-nm b { display: block; font-size: 0.95rem; }
        .rev-card .rc-nm span { color: var(--muted); font-size: 0.82rem; }

        /* Company section */
        .company-grid { display: grid; grid-template-columns: 0.95fr 1.05fr; gap: 48px; align-items: center; }
        .company-img { border-radius: 22px; overflow: hidden; box-shadow: var(--shadow); aspect-ratio: 4/3; background: var(--grad) center/cover no-repeat; }
        .company-img img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .mini-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-top: 28px; }
        .mini-stats .ms { background: var(--surface); border: 1px solid var(--border); border-radius: 14px; padding: 18px; text-align: center; }
        .mini-stats .ms .n { font-size: 1.6rem; font-weight: 800; }
        .mini-stats .ms .l { font-size: 0.8rem; color: var(--muted); }
        @media (max-width: 940px) { .company-grid { grid-template-columns: 1fr; gap: 28px; } }
        @media (max-width: 620px) { .rev-card { width: 270px; } .rating-summary .big { font-size: 2.1rem; } }

        .flash { max-width: 1180px; margin: 18px auto -20px; padding: 0 24px; }
        .flash-inner { padding: 13px 18px; border-radius: 12px; font-size: 0.92rem; font-weight: 500; }
        .flash-success { background: var(--success-soft); color: var(--success); }
        .flash-danger { background: var(--danger-soft); color: var(--danger); }

        .nav-auth { display: none; }
        @media (max-width: 940px) {
            .nav-links { position: absolute; top: 74px; left: 0; right: 0; background: var(--surface); border-bottom: 1px solid var(--border); flex-direction: column; align-items: stretch; padding: 14px 22px; gap: 4px; display: none; box-shadow: var(--shadow); }
            .nav-links.open { display: flex; }
            .nav-links a { padding: 12px 14px; }
            .nav-auth { display: flex; flex-direction: column; gap: 8px; padding-top: 12px; margin-top: 6px; border-top: 1px solid var(--border); }
            .desktop-auth { display: none; }
            .menu-toggle { display: grid; place-items: center; }
            .split, .contact-grid { grid-template-columns: 1fr; gap: 30px; }
            .cards, .steps { grid-template-columns: 1fr 1fr; }
            .hero-slide { grid-template-columns: 1fr; text-align: center; padding: 40px 30px; min-height: 0; }
            .hero-slide .hs-inner { max-width: none; }
            .hs-art { display: none; }
            .footer-grid { grid-template-columns: 1fr 1fr; gap: 26px; }
            .section { padding: 60px 0; }
        }
        @media (max-width: 620px) {
            .cards, .steps, .value-grid { grid-template-columns: 1fr; }
            .footer-grid { grid-template-columns: 1fr; }
            .track-field { flex-direction: column; }
            .track-field button { width: 100%; }
            .cta-band { padding: 36px 24px; }
            .c-arrow { width: 38px; height: 38px; } .c-prev { left: 8px; } .c-next { right: 8px; }
            .tcard { padding: 28px 20px; } .tcard p { font-size: 1.05rem; }
            .section-head h2 { font-size: 1.55rem; }
            .trust { gap: 26px; }
        }
    </style>
    @yield('head')
</head>
<body>
@php $cust = auth('customer')->user(); @endphp
<header class="site-header">
    <div class="wrap header-inner">
        <a href="{{ route('public.home') }}" class="logo">
            <span class="logo-mark">
                <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g stroke="#fff" stroke-width="2.6" stroke-linecap="round">
                        <path d="M12 27V22"/><path d="M20 27V15"/><path d="M28 27V19"/>
                    </g>
                    <path d="M11 27h18" stroke="#fff" stroke-width="2.6" stroke-linecap="round"/>
                    <circle cx="28" cy="13" r="3" fill="#fff"/>
                </svg>
            </span>
            <span class="logo-text">
                <span class="logo-name">Prime Byte</span>
                <span class="logo-sub">Building Software, Building Trust</span>
            </span>
        </a>

        <nav class="nav-links" id="navLinks">
            <a href="{{ route('public.home') }}" class="{{ request()->routeIs('public.home') ? 'active' : '' }}">Home</a>
            <a href="{{ route('public.services') }}" class="{{ request()->routeIs('public.services') ? 'active' : '' }}">Services</a>
            <a href="{{ route('public.about') }}" class="{{ request()->routeIs('public.about') ? 'active' : '' }}">About</a>
            <a href="{{ route('public.contact') }}" class="{{ request()->routeIs('public.contact') ? 'active' : '' }}">Contact</a>
            <a href="{{ route('portal.track') }}" class="{{ request()->routeIs('portal.*') ? 'active' : '' }}">Track Project</a>
            <div class="nav-auth">
                @if($cust)
                    <span style="font-size:0.82rem; color:var(--muted); padding:6px 14px;">Signed in as <strong>{{ $cust->name }}</strong></span>
                    <form method="POST" action="{{ route('customer.logout') }}">@csrf<button class="btn btn-ghost" type="submit" style="width:100%;">Logout</button></form>
                @else
                    <a href="{{ route('customer.login') }}" class="btn btn-ghost">Login</a>
                    <a href="{{ route('customer.register') }}" class="btn btn-primary">Register</a>
                @endif
            </div>
        </nav>

        <div class="header-cta">
            <button class="icon-btn theme-toggle" onclick="toggleTheme()" aria-label="Toggle theme" title="Toggle dark mode">
                <svg class="moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                <svg class="sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
            </button>
            @if($cust)
                <div class="cust-chip desktop-auth">
                    <span class="cust-av">{{ strtoupper(mb_substr($cust->name, 0, 1)) }}</span>
                    <span class="logo-text"><span class="cust-name">{{ $cust->name }}</span><span class="cust-sub">Client</span></span>
                </div>
                <form method="POST" action="{{ route('customer.logout') }}" class="desktop-auth">@csrf<button class="btn btn-ghost" type="submit">Logout</button></form>
            @else
                <a href="{{ route('customer.login') }}" class="btn btn-ghost desktop-auth">Login</a>
                <a href="{{ route('customer.register') }}" class="btn btn-primary desktop-auth">Register</a>
            @endif
            <button class="icon-btn menu-toggle" onclick="document.getElementById('navLinks').classList.toggle('open')" aria-label="Menu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>
        </div>
    </div>
</header>

@if (session('status') || session('error'))
    <div class="flash"><div class="flash-inner {{ session('error') ? 'flash-danger' : 'flash-success' }}">{{ session('error') ?? session('status') }}</div></div>
@endif

<main>
    @yield('content')
</main>

<footer class="site-footer">
    <div class="wrap">
        <div class="footer-grid">
            <div>
                <div class="footer-logo">
                    <span class="logo-mark">
                        <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g stroke="#fff" stroke-width="2.6" stroke-linecap="round"><path d="M12 27V22"/><path d="M20 27V15"/><path d="M28 27V19"/></g>
                            <path d="M11 27h18" stroke="#fff" stroke-width="2.6" stroke-linecap="round"/>
                            <circle cx="28" cy="13" r="3" fill="#fff"/>
                        </svg>
                    </span>
                    <span class="logo-name" style="color:#fff;">Prime Byte</span>
                </div>
                <p class="fdesc" style="font-style:italic; color:#c7d2e4; margin-bottom:10px;">"Building Software, Building Trust."</p>
                <p class="fdesc">Software solutions and transparent project tracking for our clients — follow your project status, payments and invoices anytime, anywhere.</p>
                <div class="fsocial">
                    <a href="#" aria-label="Facebook"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M22 12a10 10 0 1 0-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.4h-1.2c-1.2 0-1.6.8-1.6 1.6V12h2.7l-.4 2.9h-2.3v7A10 10 0 0 0 22 12z"/></svg></a>
                    <a href="#" aria-label="Twitter"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M22 5.9c-.7.3-1.5.6-2.3.7.8-.5 1.5-1.3 1.8-2.3-.8.5-1.7.8-2.6 1a4.1 4.1 0 0 0-7 3.7A11.6 11.6 0 0 1 3.4 4.6a4.1 4.1 0 0 0 1.3 5.5c-.7 0-1.3-.2-1.9-.5v.1c0 2 1.4 3.6 3.3 4a4.1 4.1 0 0 1-1.9.1 4.1 4.1 0 0 0 3.8 2.9A8.3 8.3 0 0 1 2 18.6a11.6 11.6 0 0 0 6.3 1.8c7.5 0 11.6-6.2 11.6-11.6v-.5c.8-.6 1.5-1.3 2.1-2.1z"/></svg></a>
                    <a href="#" aria-label="LinkedIn"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M4.98 3.5A2.5 2.5 0 1 0 5 8.5a2.5 2.5 0 0 0 0-5zM3 9h4v12H3zM9 9h3.8v1.7h.1c.5-1 1.8-2 3.7-2 4 0 4.7 2.6 4.7 6V21h-4v-5.3c0-1.3 0-2.9-1.8-2.9s-2 1.4-2 2.8V21H9z"/></svg></a>
                </div>
            </div>
            <div>
                <h4>Company</h4>
                <div class="flinks">
                    <a href="{{ route('public.about') }}">About Us</a>
                    <a href="{{ route('public.services') }}">Our Services</a>
                    <a href="{{ route('public.contact') }}">Contact Us</a>
                </div>
            </div>
            <div>
                <h4>Portal</h4>
                <div class="flinks">
                    <a href="{{ route('portal.track') }}">Track Project</a>
                    <a href="{{ route('customer.login') }}">Login</a>
                    <a href="{{ route('customer.register') }}">Register</a>
                </div>
            </div>
            <div>
                <h4>Get in touch</h4>
                <div class="fcontact">
                    <span>📍 Dhaka, Bangladesh</span>
                    <span>✉️ info@primebyte.com</span>
                    <span>📞 +880 1XXX-XXXXXX</span>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <span>&copy; {{ date('Y') }} Prime Byte Software Solution. All rights reserved.</span>
            <span>Client Portal v1.0</span>
        </div>
    </div>
</footer>

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
    document.querySelectorAll('[data-carousel]').forEach(function (root) {
        var track = root.querySelector('.c-track');
        var slides = root.querySelectorAll('.c-slide');
        if (!track || slides.length === 0) return;
        var index = 0, count = slides.length;
        var dotsWrap = root.querySelector('.c-dots'), dots = [];
        if (dotsWrap) {
            for (var i = 0; i < count; i++) {
                var d = document.createElement('button');
                d.className = 'c-dot' + (i === 0 ? ' active' : ''); d.type = 'button';
                (function (n) { d.addEventListener('click', function () { go(n); reset(); }); })(i);
                dotsWrap.appendChild(d); dots.push(d);
            }
        }
        function go(n) {
            index = (n + count) % count;
            track.style.transform = 'translateX(' + (-index * 100) + '%)';
            dots.forEach(function (dot, i) { dot.classList.toggle('active', i === index); });
        }
        var prev = root.querySelector('.c-prev'), next = root.querySelector('.c-next');
        if (prev) prev.addEventListener('click', function () { go(index - 1); reset(); });
        if (next) next.addEventListener('click', function () { go(index + 1); reset(); });
        var timer = setInterval(function () { go(index + 1); }, 5500);
        function reset() { clearInterval(timer); timer = setInterval(function () { go(index + 1); }, 5500); }
    });
</script>
@yield('scripts')
</body>
</html>
