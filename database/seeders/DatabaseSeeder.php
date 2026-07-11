<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $modules = array_keys(Role::modules());
        $actions = Role::actions();

        // Admin role: full access to everything.
        $adminPermissions = [];
        foreach ($modules as $module) {
            $adminPermissions[$module] = $actions;
        }
        $adminRole = Role::updateOrCreate(
            ['name' => 'Admin'],
            ['permissions' => $adminPermissions]
        );

        // Accountant role: view-only on the dashboard and operational modules.
        $accountantPermissions = [
            'dashboard' => ['view'],
            'clients' => ['view'],
            'projects' => ['view'],
            'invoices' => ['view'],
        ];
        // Rename the legacy "Employee" role if it exists, otherwise create "Accountant".
        $legacy = Role::where('name', 'Employee')->first();
        if ($legacy) {
            $legacy->update(['name' => 'Accountant', 'permissions' => $accountantPermissions]);
        } else {
            Role::updateOrCreate(['name' => 'Accountant'], ['permissions' => $accountantPermissions]);
        }

        // Sample published reviews (only if none exist yet).
        if (Review::count() === 0) {
            $samples = [
                ['name' => 'James Dawson', 'role' => 'Director, London', 'rating' => 5, 'avatar' => 'https://randomuser.me/api/portraits/men/32.jpg', 'comment' => 'Clean, fast and reliable. Managing our clients and invoices has never been this simple.'],
                ['name' => 'Sara Rahman', 'role' => 'Founder, Dhaka', 'rating' => 5, 'avatar' => 'https://randomuser.me/api/portraits/women/44.jpg', 'comment' => 'Being able to track our payment history and print invoices instantly saved us so much time.'],
                ['name' => 'Michael Adams', 'role' => 'CEO, Toronto', 'rating' => 5, 'avatar' => 'https://randomuser.me/api/portraits/men/68.jpg', 'comment' => 'The transparency is fantastic — I always know exactly where my project stands.'],
                ['name' => 'Aisha Khan', 'role' => 'Manager, Dubai', 'rating' => 5, 'avatar' => 'https://randomuser.me/api/portraits/women/68.jpg', 'comment' => 'Professional team and a beautiful portal. Highly recommended for any business.'],
                ['name' => 'David Chen', 'role' => 'Owner, Singapore', 'rating' => 4, 'avatar' => 'https://randomuser.me/api/portraits/men/45.jpg', 'comment' => 'Great communication throughout the project and the tracking codes are super handy.'],
                ['name' => 'Emily Turner', 'role' => 'COO, Sydney', 'rating' => 5, 'avatar' => 'https://randomuser.me/api/portraits/women/12.jpg', 'comment' => 'Everything in one place — status, payments and invoices. Exactly what we needed.'],
            ];
            foreach ($samples as $s) {
                Review::create($s + ['is_approved' => true]);
            }
        }

        // Super admin — bypasses all permission checks. Password is resettable via email.
        User::updateOrCreate(
            ['email' => 'alamhpl11@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('superadmin123'),
                'is_super_admin' => true,
                'designation' => 'Super Administrator',
                'role_id' => null,
            ]
        );
    }
}
