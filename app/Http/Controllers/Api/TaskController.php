<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Requests\Task\UpdateTaskStatusRequest;

class TaskController extends Controller
{

    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(Request $request)
    {
        try{
            $perPage = $request->query('per_page', config('general.pagination'));
            $filters = $request->filter ?? [];
            $tasks = $this->taskService->listTasks($filters, $perPage);
            return successResponse(TaskResource::collection($tasks), 'Tasks retrieved successfully');
        } catch (\Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

    public function store(StoreTaskRequest $request)
    {
        try{
            $task = $this->taskService->createTask($request->validated(), $request->dependencies ?? []);
            return successResponse(new TaskResource($task->load('user', 'dependencies')), 'Task created successfully');
        } catch (\Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

    public function show(Task $task)
    {
        try{
            $task = $this->taskService->getTask($task->id);
            return successResponse(new TaskResource($task), 'Task retrieved successfully');
        } catch (\Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        try{
            $task = $this->taskService->updateTask($task, $request->validated(), $request->dependencies ?? []);
            return successResponse(new TaskResource($task->load('user', 'dependencies')), 'Task updated successfully');
        } catch (\Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

    public function destroy(Task $task)
    {
        try{
            $this->taskService->deleteTask($task);
            return successResponse(null, 'Task deleted successfully');
        } catch (\Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }


    public function updateStatus(UpdateTaskStatusRequest $request, Task $task)
    {
        try {
            $user = auth()->user();
            $this->authorize('updateStatus' ,  $task);
            // $request->validate(['status' => 'required|in:pending,completed,canceled']);

            $this->taskService->updateStatus($task, $request->status);
            return successResponse(null, 'Task status updated');

        } catch (\Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

    public function addDependencies(Request $request, Task $task)
    {
        try{
            $this->authorize('update', $task);
            $request->validate([
                'dependencies' => 'required|array',
                'dependencies.*' => 'exists:tasks,id',
            ]);

            $this->taskService->assignDependencies($task, $request->dependencies);
            return successResponse(null, 'Task dependencies updated');
        } catch (\Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

}
