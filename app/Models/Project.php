<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['code', 'client_id', 'name', 'type', 'project_value', 'start_date', 'end_date', 'status', 'description'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /** Manual work-progress statuses an admin can set. */
    public static function statuses(): array
    {
        return ['Pending', 'In Progress', 'On Hold', 'Completed', 'Cancelled'];
    }

    /** Admin badge class for the current status. */
    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'Completed' => 'badge-success',
            'In Progress' => 'badge-primary',
            'On Hold' => 'badge-warning',
            'Cancelled' => 'badge-danger',
            default => 'badge-neutral',
        };
    }

    /** Public portal pill class for the current status. */
    public function statusPillClass(): string
    {
        return match ($this->status) {
            'Completed' => 's-paid',
            'In Progress', 'On Hold' => 's-progress',
            default => 's-pending',
        };
    }

    /** Generate a unique, non-guessable tracking code. */
    public static function generateCode(): string
    {
        $prefix = Setting::get('project_code_prefix') ?: 'PRJ';
        $prefix = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $prefix)) ?: 'PRJ';

        do {
            $code = $prefix . '-' . strtoupper(\Illuminate\Support\Str::random(8));
        } while (static::where('code', $code)->exists());

        return $code;
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function teamMembers()
    {
        return $this->belongsToMany(TeamMember::class, 'project_team_member')->withTimestamps();
    }

    public function teamPayments()
    {
        return $this->hasMany(TeamPayment::class);
    }

    /** Equal split: each assigned member's share of the project value. */
    public function memberShare(): float
    {
        $count = $this->teamMembers->count();

        return $count > 0 ? round(((float) $this->project_value) / $count, 2) : 0.0;
    }
}
