@extends('layouts.public')

@section('title', 'Our Services')

@section('content')
<section class="section">
    <div class="wrap">
        <div class="section-head">
            <span class="eyebrow">Our Services</span>
            <h2>Software solutions built for growth</h2>
            <p>From custom software to ongoing support, we deliver reliable solutions and keep you informed every step of the way.</p>
        </div>
        <div class="cards">
            <div class="m-card">
                <div class="ic" style="background:linear-gradient(135deg,#4f46e5,#06b6d4)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg></div>
                <h3>Custom Software</h3>
                <p>Tailored web and business applications designed around your workflow.</p>
            </div>
            <div class="m-card">
                <div class="ic" style="background:linear-gradient(135deg,#8b5cf6,#ec4899)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg></div>
                <h3>Web Development</h3>
                <p>Fast, modern, responsive websites and portals that work everywhere.</p>
            </div>
            <div class="m-card">
                <div class="ic" style="background:linear-gradient(135deg,#f59e0b,#f43f5e)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg></div>
                <h3>ERP &amp; Accounting</h3>
                <p>Systems to manage clients, projects, invoices and payments with ease.</p>
            </div>
            <div class="m-card">
                <div class="ic" style="background:linear-gradient(135deg,#16a34a,#14b8a6)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></div>
                <h3>Support &amp; Maintenance</h3>
                <p>Ongoing updates, monitoring and support to keep everything running.</p>
            </div>
            <div class="m-card">
                <div class="ic" style="background:linear-gradient(135deg,#0ea5e9,#6366f1)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a10 10 0 1 0 10 10"/><path d="M12 6v6l4 2"/></svg></div>
                <h3>Consulting</h3>
                <p>Expert guidance to plan, scope and scale your digital projects.</p>
            </div>
            <div class="m-card">
                <div class="ic" style="background:linear-gradient(135deg,#e11d48,#7c3aed)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
                <h3>Secure &amp; Reliable</h3>
                <p>Best-practice security and dependable delivery you can count on.</p>
            </div>
        </div>
    </div>
</section>

<section class="section alt">
    <div class="wrap">
        <div class="cta-band">
            <h2>Have a project in mind?</h2>
            <p>Let's talk about how we can help.</p>
            <a href="{{ route('public.contact') }}" class="btn btn-light">Contact Us</a>
        </div>
    </div>
</section>
@endsection
