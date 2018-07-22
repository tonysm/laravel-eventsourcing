<?php

namespace App\Http\Controllers;

use App\Core\Tasks\Task;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'taskName' => ['required', 'string', 'min:1'],
        ]);

        Task::createTaskForUser($request->user(), $request->input('taskName'));

        return redirect()
            ->route('home')
            ->with('status', 'Task was created successfully');
    }

    public function update(Task $task, Request $request)
    {
        abort_if($task->user->isNot($request->user()), 404);

        $task->markAsIncomplete();

        return redirect()
            ->route('home')
            ->with('status', 'Task was marked as incomplete successfully');
    }

    public function destroy(Task $task, Request $request)
    {
        abort_if($task->user->isNot($request->user()), 404);

        $task->markAsCompleted();

        return redirect()
            ->route('home')
            ->with('status', 'Task was created marked as completed successfully');
    }
}
