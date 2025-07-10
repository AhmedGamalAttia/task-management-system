<?php

namespace App\Models;

use App\Scopes\UserTaskScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'due_date',
        'user_id',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new UserTaskScope);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dependencies()
    {
        return $this->belongsToMany(Task::class, 'task_dependencies', 'task_id', 'dependent_task_id');
    }

    public function dependentOn()
    {
        return $this->belongsToMany(Task::class, 'task_dependencies', 'dependent_task_id', 'task_id');
    }


    public function scopeByAuthUser($query)
    {
        return $query->where('user_id', auth()->id());
    }

}
