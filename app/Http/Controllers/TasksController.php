<?php

namespace App\Http\Controllers;

use App\Core\Tasks\Task;
use Illuminate\Http\Request;
use App\Core\Tasks\TaskContext;

class TasksController extends Controller
{
    /**
     * @var TaskContext
     */
    private $taskContext;

    public function __construct(TaskContext $taskContext)
    {
        $this->middleware('auth');
        $this->taskContext = $taskContext;
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

        $this->taskContext->createTask(
            $request->user()->id,
            $request->input('taskName')
        );

        return redirect()
            ->route('home')
            ->with('status', 'Task was created successfully');
    }

    public function update(Task $task, Request $request)
    {
        abort_if($task->user_id !== $request->user()->id, 404);

        $this->taskContext->undoTaskCompleted($task->uuid);

        return redirect()
            ->route('home')
            ->with('status', 'Task was marked as incomplete successfully');
    }

    public function destroy(Task $task, Request $request)
    {
        abort_if($task->user_id !== $request->user()->id, 404);

        $this->taskContext->completeTask($task->uuid);

        return redirect()
            ->route('home')
            ->with('status', 'Task was created marked as completed successfully');
    }
}
