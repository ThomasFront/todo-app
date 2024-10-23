<?php

namespace App\Listeners;

use App\Events\TaskChanged;
use App\Models\TaskLog;

class LogTaskChange
{
    public function handle(TaskChanged $event)
    {
        foreach ($event->changes as $field => $values) {
            TaskLog::create([
                'task_id' => $event->task->id,
                'field_name' => $field,
                'before_value' => $values['before'],
                'after_value' => $values['after'],
                'changed_at' => now(),
            ]);
        }
    }
}
