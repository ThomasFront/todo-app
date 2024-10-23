<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskLog extends Model
{
    protected $fillable = [
        'task_id',
        'field_name',
        'before_value',
        'after_value',
        'changed_at',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
