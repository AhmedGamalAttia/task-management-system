<?php

namespace App\Http\Requests\Task;

use App\Rules\TaskDependenciesCompleted;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // dd( $this->route('task')->user_id, auth()->user()->role, auth()->user()->id);
        return auth()->user()->role === 'manager' || auth()->user()->id === $this->route('task')->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required','in:pending,completed,canceled', new TaskDependenciesCompleted($this->route('task')->id)],
        ];
    }
}
