<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->get();
        return view('customers.index', compact('customers'));
    }

    public function setStatus(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'status' => ['required', 'in:active,inactive,blocked'],
        ]);

        $customer->update(['status' => $data['status']]);

        $labels = ['active' => 'activated', 'inactive' => 'deactivated', 'blocked' => 'blocked'];

        return back()->with('status', "{$customer->name} has been {$labels[$data['status']]}.");
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return back()->with('status', 'Customer deleted successfully.');
    }
}
