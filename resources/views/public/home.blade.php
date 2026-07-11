@extends('layouts.public')

@section('title', 'Home')

@section('content')
{{-- Hero carousel with real images (softened by a gradient overlay) --}}
<section class="hero-wrap">
    <div class="wrap">
        <div class="carousel" data-carousel>
            <div class="c-track">
                <div class="c-slide"><div class="hero-slide" style="grid-template-columns:1fr; background: linear-gradient(115deg, rgba(30,27,75,0.92), rgba(67,56,202,0.62)), url('https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=1500&q=70') center/cover no-repeat;">
                    <div class="hs-inner">
                        <span class="eyebrow">✦ Client Portal</span>
                        <h2>Track your project &amp; payments in real time.</h2>
                        <p>Follow your project status, view every payment, and download invoices — anytime, anywhere.</p>
                        <div class="cta-actions" style="justify-content:flex-start;">
                            <a href="{{ route('portal.track') }}" class="btn btn-light">Track Your Project</a>
                            <a href="{{ route('customer.register') }}" class="btn btn-outline-light">Get Started</a>
                        </div>
                    </div>
                </div></div>
                <div class="c-slide"><div class="hero-slide" style="grid-template-columns:1fr; background: linear-gradient(115deg, rgba(12,74,110,0.9), rgba(37,99,235,0.6)), url('https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=1500&q=70') center/cover no-repeat;">
                    <div class="hs-inner">
                        <span class="eyebrow">✦ Our Work</span>
                        <h2>Software solutions built for growth.</h2>
                        <p>From custom applications to ERP and accounting systems — engineered to scale with your business.</p>
                        <div class="cta-actions" style="justify-content:flex-start;">
                            <a href="{{ route('public.services') }}" class="btn btn-light">Explore Services</a>
                            <a href="{{ route('public.contact') }}" class="btn btn-outline-light">Contact Us</a>
                        </div>
                    </div>
                </div></div>
                <div class="c-slide"><div class="hero-slide" style="grid-template-columns:1fr; background: linear-gradient(115deg, rgba(76,29,149,0.9), rgba(190,24,93,0.55)), url('https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=1500&q=70') center/cover no-repeat;">
                    <div class="hs-inner">
                        <span class="eyebrow">✦ Transparency</span>
                        <h2>Secure, transparent, always available.</h2>
                        <p>Create your free account and stay in control of your project and payment history.</p>
                        <div class="cta-actions" style="justify-content:flex-start;">
                            <a href="{{ route('customer.register') }}" class="btn btn-light">Create Account</a>
                            <a href="{{ route('portal.track') }}" class="btn btn-outline-light">Track Project</a>
                        </div>
                    </div>
                </div></div>
            </div>
            <button class="c-arrow c-prev" type="button" aria-label="Previous"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg></button>
            <button class="c-arrow c-next" type="button" aria-label="Next"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg></button>
            <div class="c-dots"></div>
        </div>
    </div>
</section>

{{-- Trust bar --}}
<div class="wrap">
    <div class="trust">
        <div class="t"><div class="n"><span class="gtext">{{ $stats['projects'] }}+</span></div><div class="l">Projects delivered</div></div>
        <div class="t"><div class="n"><span class="gtext">{{ $stats['clients'] }}+</span></div><div class="l">Happy clients</div></div>
        <div class="t"><div class="n"><span class="gtext">{{ $avgRating }}★</span></div><div class="l">Average rating</div></div>
        <div class="t"><div class="n"><span class="gtext">24/7</span></div><div class="l">Portal access</div></div>
    </div>
</div>

{{-- Track box --}}
<section class="section" style="padding-top:40px;">
    <div class="wrap">
        <div class="track-card">
            <h3>Have a tracking code?</h3>
            <p class="muted">Enter the code we emailed you to see your project status instantly.</p>
            <form method="GET" action="{{ route('portal.track') }}" class="track-field">
                <input type="text" name="code" placeholder="e.g. PRJ-XXXXXXXX" required>
                <button class="btn btn-primary" type="submit">Track</button>
            </form>
            <p class="muted" style="margin:14px 0 0; font-size:0.9rem;">Don't have an account? <a href="{{ route('customer.register') }}" style="color:var(--primary); font-weight:600;">Register free</a>.</p>
        </div>
    </div>
</section>

{{-- Company / About us --}}
<section class="section alt" id="about">
    <div class="wrap">
        <div class="company-grid">
            <div class="company-img" style="background: url('https://images.unsplash.com/photo-1600880292089-90a7e086ee0c?auto=format&fit=crop&w=1200&q=70') center/cover no-repeat, var(--grad);"></div>
            <div>
                <span class="eyebrow">Who we are</span>
                <h2 style="font-size:clamp(1.6rem,3vw,2.3rem); margin-bottom:8px;">Prime Byte Software Solution</h2>
                <p style="font-style:italic; color:var(--primary); font-weight:600; margin:0 0 14px;">“Building Software, Building Trust.”</p>
                <p style="color:var(--muted); font-size:1.05rem;">We are a team of engineers and problem-solvers dedicated to building reliable software for growing businesses. We believe great products are built on clear communication and complete transparency — which is exactly what this client portal delivers.</p>
                <p style="color:var(--muted);">From the first line of code to the final invoice, you stay informed at every step.</p>
                <div class="mini-stats">
                    <div class="ms"><div class="n gtext">{{ $stats['projects'] }}+</div><div class="l">Projects</div></div>
                    <div class="ms"><div class="n gtext">{{ $stats['clients'] }}+</div><div class="l">Clients</div></div>
                    <div class="ms"><div class="n gtext">5+</div><div class="l">Years</div></div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Services --}}
<section class="section" id="services">
    <div class="wrap">
        <div class="section-head">
            <span class="eyebrow">Our Services</span>
            <h2>Solutions we deliver</h2>
            <p>Reliable software and systems that keep your business moving — and keep you informed every step of the way.</p>
        </div>
        <div class="cards">
            <div class="m-card"><div class="ic" style="background:linear-gradient(135deg,#6366f1,#06b6d4)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg></div><h3>Custom Software</h3><p>Tailored web and business applications designed around your exact workflow.</p></div>
            <div class="m-card"><div class="ic" style="background:linear-gradient(135deg,#8b5cf6,#ec4899)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg></div><h3>Web Development</h3><p>Fast, modern, responsive websites and portals that work on every device.</p></div>
            <div class="m-card"><div class="ic" style="background:linear-gradient(135deg,#f59e0b,#f43f5e)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg></div><h3>ERP &amp; Accounting</h3><p>Systems to manage clients, projects, invoices and payments with ease.</p></div>
            <div class="m-card"><div class="ic" style="background:linear-gradient(135deg,#16a34a,#14b8a6)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></div><h3>Support &amp; Maintenance</h3><p>Ongoing updates, monitoring and support to keep everything running smoothly.</p></div>
            <div class="m-card"><div class="ic" style="background:linear-gradient(135deg,#0ea5e9,#6366f1)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a10 10 0 1 0 10 10"/><path d="M12 6v6l4 2"/></svg></div><h3>Consulting</h3><p>Expert guidance to plan, scope and scale your digital projects.</p></div>
            <div class="m-card"><div class="ic" style="background:linear-gradient(135deg,#e11d48,#7c3aed)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div><h3>Secure &amp; Reliable</h3><p>Best-practice security and dependable delivery you can always count on.</p></div>
        </div>
    </div>
</section>

{{-- How it works --}}
<section class="section alt">
    <div class="wrap">
        <div class="section-head"><span class="eyebrow">How it works</span><h2>Get started in three steps</h2></div>
        <div class="steps">
            <div class="step"><div class="num">1</div><h3>Register</h3><p>Create your free account and verify your email in under a minute.</p></div>
            <div class="step"><div class="num">2</div><h3>Enter your code</h3><p>Use the tracking code we email you when your project starts.</p></div>
            <div class="step"><div class="num">3</div><h3>Track everything</h3><p>View status, payments and invoices — and print them anytime.</p></div>
        </div>
    </div>
</section>

{{-- Reviews & Rating --}}
<section class="section" id="reviews">
    <div class="wrap">
        <div class="section-head" style="margin-bottom:34px;">
            <span class="eyebrow">Reviews &amp; Ratings</span>
            <h2>What our clients say</h2>
        </div>
        <div class="rating-summary">
            <div class="big gtext">{{ $avgRating }}</div>
            <div>
                <div class="rs-stars">{{ str_repeat('★', (int) round($avgRating)) }}{{ str_repeat('☆', 5 - (int) round($avgRating)) }}</div>
                <div class="rs-meta">Based on {{ $reviewCount }} client review{{ $reviewCount === 1 ? '' : 's' }}</div>
            </div>
            <a href="{{ route('portal.review') }}" class="btn btn-primary" style="margin-left:8px;">Write a Review</a>
        </div>
    </div>

    @if($reviews->count())
        <div class="rev-marquee">
            <div class="rev-track">
                @foreach($reviews->concat($reviews) as $r)
                    <div class="rev-card">
                        <div class="rc-stars">{{ str_repeat('★', (int) $r->rating) }}{{ str_repeat('☆', 5 - (int) $r->rating) }}</div>
                        <div class="rc-text">“{{ $r->comment }}”</div>
                        <div class="rc-who">
                            @if($r->avatar)
                                <span class="rc-av" style="background-image:url('{{ $r->avatar }}')"></span>
                            @else
                                <span class="rc-av" style="display:grid; place-items:center; color:#fff; font-weight:700;">{{ strtoupper(mb_substr($r->name, 0, 1)) }}</span>
                            @endif
                            <span class="rc-nm"><b>{{ $r->name }}</b><span>{{ $r->role ?: 'Client' }}</span></span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="wrap"><p class="muted" style="text-align:center;">No reviews yet — be the first to <a href="{{ route('portal.review') }}" style="color:var(--primary); font-weight:600;">write one</a>.</p></div>
    @endif
</section>

{{-- CTA --}}
<section class="section" style="padding-top:0;">
    <div class="wrap">
        <div class="cta-band">
            <h2>Ready to check your project?</h2>
            <p>Sign in or create your free account to get started.</p>
            <div class="cta-actions">
                <a href="{{ route('portal.track') }}" class="btn btn-light">Track Your Project</a>
                <a href="{{ route('customer.register') }}" class="btn btn-outline-light">Create Account</a>
            </div>
        </div>
    </div>
</section>
@endsection
