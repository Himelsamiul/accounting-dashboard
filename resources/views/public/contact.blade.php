@extends('layouts.public')

@section('title', 'Contact Us')

@section('content')
<section class="section">
    <div class="wrap">
        <div class="section-head">
            <span class="eyebrow">Contact Us</span>
            <h2>Let's talk</h2>
            <p>Questions about your project or our services? Send us a message.</p>
        </div>

        <div class="contact-grid">
            <div class="contact-info">
                <div class="ci">
                    <span class="cic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span>
                    <div><h4>Office</h4><p style="color:var(--muted); margin:0;">Dhaka, Bangladesh</p></div>
                </div>
                <div class="ci">
                    <span class="cic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></span>
                    <div><h4>Email</h4><p style="color:var(--muted); margin:0;">info@primebyte.com</p></div>
                </div>
                <div class="ci">
                    <span class="cic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg></span>
                    <div><h4>Phone</h4><p style="color:var(--muted); margin:0;">+880 1XXX-XXXXXX</p></div>
                </div>
            </div>

            <div class="form-card">
                <form method="POST" action="{{ route('public.contact.submit') }}">
                    @csrf
                    <div class="fld">
                        <label>Your Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required>
                        @error('name')<span style="color:var(--danger); font-size:0.82rem;">{{ $message }}</span>@enderror
                    </div>
                    <div class="fld">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required>
                        @error('email')<span style="color:var(--danger); font-size:0.82rem;">{{ $message }}</span>@enderror
                    </div>
                    <div class="fld">
                        <label>Message</label>
                        <textarea name="message" rows="5" required>{{ old('message') }}</textarea>
                        @error('message')<span style="color:var(--danger); font-size:0.82rem;">{{ $message }}</span>@enderror
                    </div>
                    <button class="btn btn-primary" type="submit" style="width:100%; justify-content:center;">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
