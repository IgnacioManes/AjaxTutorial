<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    public function store(Request $request)
    {

        $request->validate([
            'task' => 'required|max:25',
            'description' => 'required|max:25',
        ]);
        $task= Task::find($request->task_id);
        $task->task = $request->task;
        $task->description = $request->description;
        $task->save();
        return \Response::json($task);
    }
    public function add(Request $request)
    {

        $request->validate([
            'task' => 'required|max:25',
            'description' => 'required|max:25',
        ]);
        $task= new Task;
        $task->task = $request->task;
        $task->description = $request->description;
        $task->done = false;
        $task->save();
        return \Response::json($task);
    }
}
