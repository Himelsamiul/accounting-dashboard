<?php

namespace App\Support;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

class ActivityLogger
{
    /**
     * Record an activity. Never let logging break the triggering action.
     *
     * @param string      $action      login | logout | created | updated | deleted | ...
     * @param Model|null  $subject     the affected model (optional)
     * @param string|null $description human-readable summary (optional)
     * @param array|null  $changes     changed fields for updates (optional)
     */
    public static function log(string $action, ?Model $subject = null, ?string $description = null, ?array $changes = null): void
    {
        try {
            $user = auth()->user(); // web guard admin/staff

            $subjectType = $subject ? class_basename($subject) : null;
            $label = $subject ? static::labelFor($subject) : null;

            if ($description === null) {
                $description = static::describe($action, $subjectType, $label);
            }

            ActivityLog::create([
                'user_id' => $user?->id,
                'user_name' => $user?->name ?? 'System',
                'user_role' => $user
                    ? ($user->isSuperAdmin() ? 'Super Admin' : ($user->role->name ?? 'No role'))
                    : null,
                'action' => $action,
                'subject_type' => $subjectType,
                'subject_id' => $subject?->getKey(),
                'subject_label' => $label,
                'description' => $description,
                'changes' => $changes ?: null,
                'ip_address' => request()->ip(),
            ]);
        } catch (\Throwable $e) {
            // Silent: activity logging must never interrupt the real work.
        }
    }

    /** Best-effort human-readable label for a model. */
    public static function labelFor(Model $model): string
    {
        foreach (['name', 'title', 'invoice_number', 'code', 'email'] as $attr) {
            if (! empty($model->{$attr})) {
                return (string) $model->{$attr};
            }
        }

        return '#' . $model->getKey();
    }

    protected static function describe(string $action, ?string $type, ?string $label): string
    {
        $verb = [
            'created' => 'Created',
            'updated' => 'Updated',
            'deleted' => 'Deleted',
            'login' => 'Logged in',
            'logout' => 'Logged out',
        ][$action] ?? ucfirst($action);

        if (! $type) {
            return $verb;
        }

        return trim($verb . ' ' . $type . ($label ? ' “' . $label . '”' : ''));
    }
}
