<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->latest()->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role_id' => ['required', 'exists:roles,id'],
            'designation' => ['nullable', 'string', 'max:255'],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $data['role_id'],
            'designation' => $data['designation'] ?? null,
            'is_super_admin' => false,
        ]);

        return redirect()->route('users.index')->with('status', 'User created successfully.');
    }

    public function edit(User $user)
    {
        abort_if($user->isSuperAdmin(), 403, 'The super admin account cannot be managed here.');
        $roles = Role::orderBy('name')->get();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        abort_if($user->isSuperAdmin(), 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'role_id' => ['required', 'exists:roles,id'],
            'designation' => ['nullable', 'string', 'max:255'],
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role_id = $data['role_id'];
        $user->designation = $data['designation'] ?? null;
        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        return redirect()->route('users.index')->with('status', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        abort_if($user->isSuperAdmin(), 403, 'The super admin account cannot be deleted.');

        $user->delete();

        return redirect()->route('users.index')->with('status', 'User deleted successfully.');
    }

    /** Admin generates a fresh password when a user forgets theirs. */
    public function generatePassword(User $user)
    {
        abort_if($user->isSuperAdmin(), 403);

        $newPassword = Str::password(10, symbols: false);
        $user->password = Hash::make($newPassword);
        $user->save();

        return redirect()->route('users.index')
            ->with('status', "New password for {$user->name}: {$newPassword} — share it securely; it won't be shown again.");
    }
}
