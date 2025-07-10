<?php

namespace App\Http\Requests\Task;

use App\Rules\TaskDependenciesCompleted;
use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->role === 'manager';
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => ['sometimes','in:pending,completed,canceled', new TaskDependenciesCompleted($this->route('id'))],
            'due_date' => 'nullable|date',
            'user_id' => 'required|exists:users,id',
            'dependencies' => 'nullable|array',
            'dependencies.*' => 'exists:tasks,id',
        ];
    }
}
