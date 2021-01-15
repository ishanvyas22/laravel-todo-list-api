<?php

namespace App\Http\Controllers;

use App\Helpers\SetsJsonResponse;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use SetsJsonResponse;

    /**
     * Store a new task or subtask.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $task = Task::create([
            'title' => $request->title,
            'due_date' => $request->due_date,
        ]);

        return $this->setSuccessResponse([
            'success' => true,
            'task' => $task->toArray(),
        ], 201);
    }
}
