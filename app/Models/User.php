<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'designation',
        'is_super_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_super_admin' => 'boolean',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function isSuperAdmin(): bool
    {
        return (bool) $this->is_super_admin;
    }

    /** Whether this user may perform an action on a module. */
    public function hasPermission(string $module, string $action = 'view'): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $permissions = $this->role?->permissions ?? [];

        return in_array($action, $permissions[$module] ?? [], true);
    }

    public function canView(string $module): bool
    {
        return $this->hasPermission($module, 'view');
    }

    /** First section this user is allowed to see after login (null if none). */
    public function landingRoute(): ?string
    {
        $map = [
            'dashboard' => 'dashboard',
            'clients' => 'clients.index',
            'projects' => 'projects.index',
            'banks' => 'banks.index',
            'invoices' => 'invoices.index',
            'fully_paid' => 'fully-paid.index',
            'users' => 'users.index',
        ];

        foreach ($map as $module => $routeName) {
            if ($this->canView($module)) {
                return route($routeName);
            }
        }

        return null;
    }
}
