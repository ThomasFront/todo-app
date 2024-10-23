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
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

#[Prefix('api/tasks')]
class TaskController extends Controller
{
    protected TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Display a listing of the tasks, with optional status filtering.
     *
     * @param Request $request The incoming HTTP request.
     * @return JsonResponse The JSON response containing a collection of tasks.
     */
    #[Get('')]
    public function index(Request $request): JsonResponse
    {
        $status = $request->query('status', 'all');
        $tasks = $this->taskService->getAllTasks($status);

        return response()->json(new TaskCollection($tasks));
    }

    /**
     * Store a newly created task.
     *
     * @param StoreTaskRequest $request The request containing validated task data.
     * @return JsonResponse The JSON response containing the created task.
     */
    #[Post('')]
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $createdTask = $this->taskService->createTask($request);

        return response()->json(new TaskResource($createdTask), ResponseAlias::HTTP_CREATED);
    }

    /**
     * Display the specified task by ID.
     *
     * @param Task $task The task instance.
     * @return JsonResponse The JSON response with task data.
     */
    #[Get('{task}')]
    public function show(Task $task): JsonResponse
    {
        return response()->json(new TaskResource($task));
    }

    /**
     * Update the specified task.
     *
     * @param UpdateTaskRequest $request The request containing validated task data.
     * @param Task $task The task instance to update.
     * @return JsonResponse The JSON response containing the updated task.
     */
    #[Patch('{task}')]
    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $updatedTask = $this->taskService->updateTask($request, $task);

        return response()->json(new TaskResource($updatedTask));
    }

    /**
     * Toggle the completion status of the specified task.
     *
     * @param ToggleCompletionRequest $request The request containing the completion status.
     * @param Task $task The task instance to update.
     * @return JsonResponse The JSON response containing the updated task with new completion status.
     */
    #[Patch('{task}/complete')]
    public function toggleCompletion(ToggleCompletionRequest $request, Task $task): JsonResponse
    {
        $isCompleted = $request->input('is_completed');
        $updatedTask = $this->taskService->toggleCompletion($task, $isCompleted);

        return response()->json(new TaskResource($updatedTask));
    }

    /**
     * Remove the specified task from storage (soft delete).
     *
     * @param Task $task The task instance to delete.
     * @return JsonResponse The JSON response confirming the deletion.
     */
    #[Delete('{task}')]
    public function destroy(Task $task): JsonResponse
    {
        $this->taskService->deleteTask($task);

        return response()->json(null, ResponseAlias::HTTP_NO_CONTENT);
    }
}
