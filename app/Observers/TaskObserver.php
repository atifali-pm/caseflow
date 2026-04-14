<?php

namespace App\Observers;

use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;

class TaskObserver
{
    public function created(Task $task): void
    {
        if ($task->assigned_to && $task->assigned_to !== auth()->id()) {
            $assignee = User::find($task->assigned_to);
            $assignee?->notify(new TaskAssignedNotification($task));
        }
    }
}
