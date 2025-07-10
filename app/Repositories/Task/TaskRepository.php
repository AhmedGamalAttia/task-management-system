<?php

namespace App\Repositories\Task;

use App\Models\Task;
use App\Filters\TaskFilters;
use App\Repositories\Task\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    protected $model;

    public function __construct(Task $task)
    {
        $this->model = $task;
    }

    public function getAllWithFilters(array $filters, $perPage)
    {
        // $query = Task::with('user', 'dependencies', 'dependentOn');
        // $query = TaskFilters::apply($query);

        $query = TaskFilters::apply(Task::with('user', 'dependencies', 'dependentOn'), $filters);

        return $perPage === '-1'
            ? $query->latest()->get()
            : $query->latest()->paginate($perPage);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function find($id)
    {
        return $this->model->with('user', 'dependencies')->findOrFail($id);
    }

    public function update(Task $task, array $data)
    {
        $task->update($data);
        return $task;
    }

    public function delete(Task $task)
    {
        $task->delete();
    }

    public function updateStatus(Task $task, string $status)
    {
        $task->update(['status' => $status]);
        return $task;
    }

    public function attachDependencies(Task $task, array $dependencies)
    {
        $task->dependencies()->attach($dependencies);
    }

    public function syncDependencies(Task $task, array $dependencies)
    {
        $task->dependencies()->sync($dependencies);
    }

}
