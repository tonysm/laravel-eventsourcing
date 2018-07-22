<?php

namespace App\Core\Tasks\Reactors;

use App\User;
use App\Core\Tasks\Events;
use App\Core\Tasks\TasksRepository;
use Spatie\EventProjector\EventHandlers\EventHandler;
use Spatie\EventProjector\EventHandlers\HandlesEvents;

class SecondCompletedTaskReactor implements EventHandler
{
    use HandlesEvents;

    public $handlesEvents = [
        Events\TaskWasCompleted::class => 'onTaskWasCompleted',
    ];

    /**
     * @var TasksRepository
     */
    private $tasks;

    public function __construct(TasksRepository $tasks)
    {
        $this->tasks = $tasks;
    }

    public function onTaskWasCompleted(Events\TaskWasCompleted $event)
    {
        $task = $this->tasks->findByUuid($event->taskId);
        /** @var User $user */
        $user = User::findOrFail($task->user_id);

        if (is_null($user->second_completed_task_at) && $this->tasks->getCompletedTasksCountForUser($user->id) === 2) {
            $user->sendCongratulationsOnSecondTask();
        }
    }
}
