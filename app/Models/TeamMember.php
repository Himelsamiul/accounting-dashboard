<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = ['name', 'phone', 'role', 'is_active', 'notes'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_team_member')->withTimestamps();
    }

    public function payments()
    {
        return $this->hasMany(TeamPayment::class);
    }
}
