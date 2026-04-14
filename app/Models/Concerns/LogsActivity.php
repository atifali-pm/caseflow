<?php

namespace App\Models\Concerns;

use App\Models\ActivityLog;

trait LogsActivity
{
    public static function bootLogsActivity(): void
    {
        static::created(function ($model) {
            $model->logActivity('created', 'was created');
        });

        static::updated(function ($model) {
            $changes = $model->getChanges();
            unset($changes['updated_at']);

            if (empty($changes)) {
                return;
            }

            $model->logActivity('updated', 'was updated', $changes);
        });

        static::deleted(function ($model) {
            $model->logActivity('deleted', 'was deleted');
        });
    }

    public function logActivity(string $event, string $description, ?array $changes = null): void
    {
        ActivityLog::create([
            'subject_type' => static::class,
            'subject_id' => $this->getKey(),
            'user_id' => auth()->id(),
            'event' => $event,
            'description' => $description,
            'changes' => $changes,
        ]);
    }

    public function activities()
    {
        return $this->morphMany(ActivityLog::class, 'subject')->latest();
    }
}
