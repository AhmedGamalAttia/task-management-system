<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    public function view(User $user, Task $task)
    {

        return $user->role === 'manager' || $user->id === $task->user_id;
    }

    public function create(User $user)
    {
        return $user->role === 'manager';
    }

    public function update(User $user, Task $task)
    {
        return $user->role === 'manager';
    }

    public function delete(User $user, Task $task)
    {
        return $user->role === 'manager';
    }

    public function updateStatus(User $user, Task $task)
    {

        return $user->role === 'manager' || $user->id === $task->user_id;
    }
}
