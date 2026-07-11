<?php

namespace App\Observers;

use App\Support\ActivityLogger;
use Illuminate\Database\Eloquent\Model;

class ActivityObserver
{
    /** Attributes that shouldn't, on their own, count as a meaningful change. */
    protected array $ignore = [
        'updated_at', 'created_at', 'remember_token', 'password',
        'is_read', 'handled_at', 'email_verified_at',
        'otp_code', 'otp_expires_at', 'remember_token',
    ];

    public function created(Model $model): void
    {
        ActivityLogger::log('created', $model);
    }

    public function updated(Model $model): void
    {
        $changed = collect($model->getChanges())
            ->except($this->ignore)
            ->keys()
            ->all();

        // Nothing meaningful changed (e.g. only a token/timestamp) — skip the noise.
        if (empty($changed)) {
            return;
        }

        ActivityLogger::log('updated', $model, null, ['fields' => $changed]);
    }

    public function deleted(Model $model): void
    {
        ActivityLogger::log('deleted', $model);
    }
}
