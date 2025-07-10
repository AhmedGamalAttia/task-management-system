<?php
namespace App\Services;

use App\Models\Task;
use App\Repositories\Task\TaskRepositoryInterface;

class TaskService
{
    protected $taskRepo;

    public function __construct(TaskRepositoryInterface $taskRepo)
    {
        $this->taskRepo = $taskRepo;
    }

    public function listTasks(array $filters, $perPage)
    {
        return $this->taskRepo->getAllWithFilters($filters, $perPage);
    }

    public function createTask(array $data, array $dependencies = [])
    {
        $task = $this->taskRepo->create($data);

        if (!empty($dependencies)) {
            $this->taskRepo->attachDependencies($task, $dependencies);
        }

        return $task;
    }

    public function getTask(int $id)
    {
        return $this->taskRepo->find($id);
    }

    public function updateTask(Task $task, array $data, array $dependencies = [])
    {
        if (!empty($dependencies)) {
            $this->taskRepo->syncDependencies($task, $dependencies);
        }

        return $this->taskRepo->update($task, $data);
    }

    public function deleteTask(Task $task)
    {
        $this->taskRepo->delete($task);
    }

    public function updateStatus(Task $task, string $status)
    {
        // Check if task has incomplete dependencies
        if ($status === 'completed' && $task->dependencies()->where('status', '!=', 'completed')->exists()) {
            throw new \Exception('Cannot complete task until all dependencies are completed.');
        }

        return $this->taskRepo->updateStatus($task, $status);
    }

    public function assignDependencies(Task $task, array $dependencyIds)
    {
        $this->taskRepo->syncDependencies($task, $dependencyIds);
    }
}
