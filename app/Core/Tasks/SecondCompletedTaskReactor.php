<?php

namespace App\Core\Tasks;

use App\Core\Tasks\Events;
use App\Notifications\SecondCompletedTask;
use Spatie\EventProjector\EventHandlers\EventHandler;
use Spatie\EventProjector\EventHandlers\HandlesEvents;

class SecondCompletedTaskReactor implements EventHandler
{
    use HandlesEvents;

    public $handlesEvents = [
        Events\TaskWasCompleted::class => 'onTaskWasCompleted',
    ];

    public function onTaskWasCompleted(Events\TaskWasCompleted $event)
    {
        $task = Task::findByUuidOrFail($event->taskId);

        if ($task->user->tasks()->completed()->count() === 2) {
            $task->user->notify(new SecondCompletedTask());
        }
    }
}
