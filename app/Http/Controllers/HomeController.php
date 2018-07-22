<?php

namespace App\Http\Controllers;

use App\Core\Tasks\TaskContext;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, TaskContext $taskContext)
    {
        $tasks = $taskContext->getIncompleteTasksForUser($request->user()->id);
        $completedTasks = $taskContext->getCompletedTasksForUser($request->user()->id);

        return view('home', compact('tasks', 'completedTasks'));
    }
}
