<?php

namespace App\Rules;

use App\Models\Task;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TaskDependenciesCompleted implements ValidationRule
{
    protected $taskId;

    public function __construct($taskId)
    {
        $this->taskId = $taskId;
    }

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value !== 'completed') {
            return; 
        }

        $task = Task::find($this->taskId);
        if (!$task) {
            $fail('The task does not exist.');
            return;
        }

        if ($task->dependencies()->where('status', '!=', 'completed')->exists()) {
            $fail('Cannot complete task until all dependencies are completed.');
        }
    }
}