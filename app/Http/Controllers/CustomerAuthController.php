<?php

namespace App\Http\Controllers;

use App\Mail\CustomerOtpMail;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class CustomerAuthController extends Controller
{
    public function showRegister()
    {
        return view('portal.auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:customers,email'],
            'phone' => ['required', 'string', 'max:40'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $customer = Customer::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'status' => 'active',
        ]);

        \App\Models\AdminNotification::record('New portal customer registered: ' . $customer->name, [
            'type' => 'customer',
            'body' => $customer->email,
            'url' => route('customers.index'),
            'icon' => 'user',
        ]);

        $this->sendOtp($customer);
        session(['otp_customer_id' => $customer->id]);

        return redirect()->route('customer.otp')
            ->with('status', 'We sent a 6-digit verification code to ' . $customer->email . '.');
    }

    public function showOtp(Request $request)
    {
        $customer = $this->otpCustomer();
        if (! $customer) {
            return redirect()->route('customer.register');
        }

        return view('portal.auth.verify-otp', [
            'email' => $customer->email,
            'expiresAt' => optional($customer->otp_expires_at)->toIso8601String(),
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $customer = $this->otpCustomer();
        if (! $customer) {
            return redirect()->route('customer.register');
        }

        $request->validate(['otp' => ['required', 'digits:6']]);

        if (! $customer->otpMatches($request->input('otp'))) {
            $expired = $customer->otp_expires_at && $customer->otp_expires_at->isPast();
            return back()->withErrors([
                'otp' => $expired ? 'This code has expired. Please resend a new one.' : 'The code you entered is incorrect.',
            ]);
        }

        $customer->markVerified();
        session()->forget('otp_customer_id');
        Auth::guard('customer')->login($customer);

        return redirect()->route('portal.track')->with('status', 'Your email is verified. Welcome, ' . $customer->name . '!');
    }

    public function resendOtp(Request $request)
    {
        $customer = $this->otpCustomer();
        if (! $customer) {
            return redirect()->route('customer.register');
        }

        $this->sendOtp($customer);

        return back()->with('status', 'A new verification code has been sent to ' . $customer->email . '.');
    }

    public function showLogin()
    {
        return view('portal.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $customer = Customer::where('email', $credentials['email'])->first();

        if (! $customer || ! Hash::check($credentials['password'], $customer->password)) {
            return back()->withErrors(['email' => 'These credentials do not match our records.'])->onlyInput('email');
        }

        if ($customer->status === 'blocked') {
            return back()->withErrors(['email' => 'Your account has been blocked. Please contact support.'])->onlyInput('email');
        }

        if ($customer->status !== 'active') {
            return back()->withErrors(['email' => 'Your account is not active. Please contact support.'])->onlyInput('email');
        }

        // Force email verification before first sign-in.
        if (! $customer->isVerified()) {
            $this->sendOtp($customer);
            session(['otp_customer_id' => $customer->id]);
            return redirect()->route('customer.otp')
                ->with('status', 'Please verify your email. We sent a new code to ' . $customer->email . '.');
        }

        Auth::guard('customer')->login($customer, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('portal.track'));
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        $request->session()->regenerate();

        return redirect()->route('public.home')->with('status', 'You have been signed out.');
    }

    private function otpCustomer(): ?Customer
    {
        $id = session('otp_customer_id');
        return $id ? Customer::find($id) : null;
    }

    private function sendOtp(Customer $customer): void
    {
        $otp = $customer->generateOtp();
        try {
            Mail::to($customer->email)->send(new CustomerOtpMail($customer, $otp));
        } catch (\Throwable $e) {
            // Silently ignore mail failures; the user can resend.
        }
    }
}
