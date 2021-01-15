<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['parent_id', 'title', 'due_date'];

    /**
     * Main tasks.
     *
     * @return \App\Models\Task
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Subtasks associated with task.
     *
     * @return \App\Models\Task
     */
    public function subtasks()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
