<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureCustomerActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $customer = Auth::guard('customer')->user();

        if ($customer && $customer->status !== 'active') {
            Auth::guard('customer')->logout();

            return redirect()->route('customer.login')
                ->withErrors(['email' => 'Your account is no longer active. Please contact support.']);
        }

        return $next($request);
    }
}
