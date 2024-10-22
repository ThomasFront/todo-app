<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\ToggleCompletionRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class TaskController extends Controller
{
    protected TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $status = $request->query('status', 'all');
        $tasks = $this->taskService->getAllTasks($status);

        return response()->json(new TaskCollection($tasks));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $createdTask = $this->taskService->createTask($request);

        return response()->json(new TaskResource($createdTask), ResponseAlias::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): JsonResponse
    {
        return response()->json(new TaskResource($task));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $updatedTask = $this->taskService->updateTask($request, $task);

        return response()->json(new TaskResource($updatedTask));
    }

    public function toggleCompletion(ToggleCompletionRequest $request, Task $task): JsonResponse
    {
        $isCompleted = $request->input('is_completed');
        $updatedTask = $this->taskService->toggleCompletion($task, $isCompleted);

        return response()->json(new TaskResource($updatedTask));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): JsonResponse
    {
        $this->taskService->deleteTask($task);

        return response()->json(null, ResponseAlias::HTTP_NO_CONTENT);
    }
}
