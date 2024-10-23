<?php

namespace App\Services;

use App\Events\TaskChanged;
use App\Events\TaskCompleted;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Support\Collection;

class TaskService
{
    /**
     * Retrieve tasks from the database.
     *
     * @param string $status
     * @return Collection
     */
    public function getAllTasks(string $status = 'all'): Collection
    {
        switch ($status) {
            case 'completed':
                return Task::where('is_completed', true)->get();
            case 'incomplete':
                return Task::where('is_completed', false)->get();
            case 'all':
            default:
                return Task::all();
        }
    }

    /**
     * Create a new task.
     *
     * @param StoreTaskRequest $request
     * @return Task
     */
    public function createTask(StoreTaskRequest $request): Task
    {
        $validatedData = $request->validated();
        $validatedData['is_completed'] = false;

        return Task::create($validatedData);
    }

    /**
     * Toggle the completion status of a task.
     *
     * @param Task $task
     * @param bool $isCompleted
     * @return Task
     */
    public function toggleCompletion(Task $task, bool $isCompleted): Task
    {
        $task->is_completed = $isCompleted;
        $task->save();

        if ($isCompleted) {
            event(new TaskCompleted($task));
        }

        return $task;
    }

    /**
     * Update an existing task.
     *
     * @param UpdateTaskRequest $request
     * @param Task $task
     * @return Task
     */
    public function updateTask(UpdateTaskRequest $request, Task $task): Task
    {
        $originalValues = $task->getOriginal();

        $validatedData = $request->validated();
        $task->update($validatedData);

        $newValues = $task->getAttributes();

        $changes = [];

        foreach ($newValues as $field => $newValue) {
            if ($newValue !== $originalValues[$field]) {
                $changes[$field] = [
                    'before' => $originalValues[$field],
                    'after' => $newValue,
                ];
            }
        }

        if (!empty($changes)) {
            event(new TaskChanged($task, $changes));
        }

        return $task;
    }

    /**
     * Delete a task by ID.
     *
     * @param Task $task
     * @return void
     */
    public function deleteTask(Task $task): void
    {
        $task->delete();
    }
}


