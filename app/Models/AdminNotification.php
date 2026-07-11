<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    protected $fillable = ['type', 'title', 'body', 'url', 'icon', 'is_read'];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /** Create an admin notification. Never let a failure break the triggering action. */
    public static function record(string $title, array $opts = []): ?self
    {
        try {
            return static::create([
                'title' => $title,
                'type' => $opts['type'] ?? 'info',
                'body' => $opts['body'] ?? null,
                'url' => $opts['url'] ?? null,
                'icon' => $opts['icon'] ?? null,
            ]);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
