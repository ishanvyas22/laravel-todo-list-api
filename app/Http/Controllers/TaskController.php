<?php

namespace App\Http\Controllers;

use App\Helpers\SetsJsonResponse;
use App\Models\Task;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use SoftDeletes, SetsJsonResponse;

    /**
     * Store a new task or subtask.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $task = Task::create([
            'parent_id' => $request->parent_id,
            'title' => $request->title,
            'due_date' => $request->due_date,
        ]);

        return $this->setSuccessResponse($task->toArray(), 201);
    }

    /**
     * Lists all the tasks along with subtasks.
     *
     * @return Response
     */
    public function index()
    {
        $tasks = Task::with('subtasks')->where([
            'status' => false,
            'parent_id' => null,
            'deleted_at' => null,
        ])->orderBy('due_date')->paginate(15);

        return $this->setSuccessResponse($tasks->toArray());
    }

    /**
     * Marks a task or a subtask complete.
     *
     * @param  Request  $request
     * @return Response
     */
    public function complete(Request $request)
    {
        $task = Task::findOrFail($request->task_id);

        $task->status = true;

        $task->save();

        return $this->setSuccessResponse(['message' => 'Task has been completed.']);
    }
}
