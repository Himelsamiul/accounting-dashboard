<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    public function index()
    {
        $company = Setting::company();
        $project = Setting::project();
        $user = auth()->user();
        $canEditSettings = $user->hasPermission('settings', 'edit');

        return view('settings.index', compact('company', 'project', 'user', 'canEditSettings'));
    }

    /** Company + project-wide settings (gated by perm:settings,edit on the route). */
    public function updateCompany(Request $request)
    {
        $data = $request->validate([
            'company_name' => ['nullable', 'string', 'max:120'],
            'company_tagline' => ['nullable', 'string', 'max:160'],
            'company_email' => ['nullable', 'email', 'max:160'],
            'company_phone' => ['nullable', 'string', 'max:60'],
            'company_address' => ['nullable', 'string', 'max:400'],
            'currency_symbol' => ['nullable', 'string', 'max:8'],
            'project_code_prefix' => ['nullable', 'string', 'max:12'],
            'project_types' => ['nullable', 'string', 'max:2000'],
            'default_project_value' => ['nullable', 'numeric', 'min:0'],
        ]);

        foreach ($data as $key => $value) {
            // Normalise the code prefix (uppercase, no spaces).
            if ($key === 'project_code_prefix' && $value) {
                $value = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $value));
            }
            Setting::put($key, $value ?? '');
        }

        return back()->with('status', 'Settings saved successfully.');
    }

    /** Self-service account details for the logged-in user. */
    public function updateAccount(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $user->update($data);

        return back()->with('status', 'Your account details have been updated.');
    }

    /** Self-service password change. */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = auth()->user();

        if (! Hash::check($request->input('current_password'), $user->password)) {
            return back()->withErrors(['current_password' => 'Your current password is incorrect.']);
        }

        $user->update(['password' => Hash::make($request->input('password'))]);

        return back()->with('status', 'Password changed successfully.');
    }
}
