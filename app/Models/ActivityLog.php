<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'user_name', 'user_role', 'action',
        'subject_type', 'subject_id', 'subject_label',
        'description', 'changes', 'ip_address',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Distinct actions present in the log (for the filter dropdown). */
    public static function distinctActions(): array
    {
        return static::query()->distinct()->orderBy('action')->pluck('action')->all();
    }
}
