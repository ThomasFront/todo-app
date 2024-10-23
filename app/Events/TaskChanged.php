<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskChanged
{
    use Dispatchable, SerializesModels;

    public Task $task;
    public array $changes;

    public function __construct(Task $task, array $changes)
    {
        $this->task = $task;
        $this->changes = $changes;
    }
}
