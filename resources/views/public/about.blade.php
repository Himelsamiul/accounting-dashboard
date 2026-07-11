@extends('layouts.public')

@section('title', 'About Us')

@section('content')
<section class="section">
    <div class="wrap">
        <div class="section-head">
            <span class="eyebrow">About Us</span>
            <h2>We build software and lasting trust</h2>
        </div>
        <div class="prose">
            <p>Prime Byte Software Solution is a team of engineers and problem-solvers dedicated to building reliable software for growing businesses. We believe great products are built on clear communication and complete transparency.</p>
            <p>That's why we created this client portal — so you're never left wondering about the status of your project or your payments. Everything you need is available to you, anytime, in one secure place.</p>
        </div>

        <div class="value-grid prose" style="max-width:820px;">
            <div class="value-item">
                <span class="vic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></span>
                <div><h4>Transparency</h4><p>Real-time visibility into your project and payment history.</p></div>
            </div>
            <div class="value-item">
                <span class="vic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg></span>
                <div><h4>Speed</h4><p>Fast delivery without compromising on quality or reliability.</p></div>
            </div>
            <div class="value-item">
                <span class="vic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></span>
                <div><h4>Security</h4><p>Your data and documents are protected at every step.</p></div>
            </div>
            <div class="value-item">
                <span class="vic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></span>
                <div><h4>Partnership</h4><p>We treat every client relationship as a long-term partnership.</p></div>
            </div>
        </div>
    </div>
</section>
@endsection
