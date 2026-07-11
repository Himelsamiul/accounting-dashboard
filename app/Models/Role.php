<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'permissions'];

    protected $casts = [
        'permissions' => 'array',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    /** Modules and their available actions used across the app. */
    public static function modules(): array
    {
        return [
            'dashboard' => 'Dashboard',
            'clients' => 'Clients',
            'projects' => 'Projects',
            'banks' => 'Banks',
            'invoices' => 'Invoices',
            'fully_paid' => 'Fully Paid',
            'customers' => 'Portal Customers',
            'reviews' => 'Reviews',
            'contacts' => 'Contact Messages',
            'users' => 'Users & Roles',
        ];
    }

    public static function actions(): array
    {
        return ['view', 'create', 'edit', 'delete'];
    }

    /** Which actions actually apply to a module (view-only sections have no CRUD). */
    public static function actionsFor(string $module): array
    {
        return match ($module) {
            'dashboard', 'fully_paid' => ['view'],
            'customers', 'reviews' => ['view', 'edit', 'delete'],
            'contacts' => ['view', 'delete'],
            default => self::actions(),
        };
    }
}
