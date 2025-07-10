<?php
namespace App\Repositories\Task;

use App\Models\Task;

interface TaskRepositoryInterface
{
    public function getAllWithFilters(array $filters, $perPage);
    public function create(array $data);
    public function find($id);
    public function update(Task $task, array $data);
    public function delete(Task $task);
    public function updateStatus(Task $task, string $status);
    public function attachDependencies(Task $task, array $dependencies);
    public function syncDependencies(Task $task, array $dependencies);
}
