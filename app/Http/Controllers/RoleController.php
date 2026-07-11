<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')->latest()->get();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
        ]);

        Role::create([
            'name' => $data['name'],
            'permissions' => $this->cleanPermissions($request->input('permissions', [])),
        ]);

        return redirect()->route('roles.index')->with('status', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name,' . $role->id],
        ]);

        $role->update([
            'name' => $data['name'],
            'permissions' => $this->cleanPermissions($request->input('permissions', [])),
        ]);

        return redirect()->route('roles.index')->with('status', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        if ($role->users()->exists()) {
            return redirect()->route('roles.index')->withErrors([
                'role' => 'Cannot delete a role that still has users assigned.',
            ]);
        }

        $role->delete();

        return redirect()->route('roles.index')->with('status', 'Role deleted successfully.');
    }

    /** Keep only valid module => [actions]. */
    private function cleanPermissions(array $input): array
    {
        $modules = array_keys(Role::modules());
        $actions = Role::actions();
        $clean = [];

        foreach ($modules as $module) {
            $selected = array_values(array_intersect($actions, (array) ($input[$module] ?? [])));
            if (! empty($selected)) {
                $clean[$module] = $selected;
            }
        }

        return $clean;
    }
}
