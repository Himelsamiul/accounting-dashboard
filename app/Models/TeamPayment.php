<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamPayment extends Model
{
    protected $fillable = ['project_id', 'team_member_id', 'amount', 'method', 'bank_id', 'paid_on', 'note'];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_on' => 'date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function teamMember()
    {
        return $this->belongsTo(TeamMember::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
}
