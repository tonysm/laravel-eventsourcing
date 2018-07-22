<?php

namespace App\Core\Tasks;

use App\User;
use App\Core\Tasks\Events;
use Illuminate\Support\Carbon;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

class TasksProjector implements Projector
{
    use ProjectsEvents;

    public $handlesEvents = [
        Events\TaskWasCreated::class => 'onTaskWasCreated',
        Events\TaskWasCompleted::class => 'onTaskWasCompleted',
        Events\TaskWasMarkedAsIncomplete::class => 'onTaskWasMarkedAsIncomplete',
    ];

    /**
     * @var TasksRepository
     */
    private $tasks;

    public function __construct(TasksRepository $tasks)
    {
        $this->tasks = $tasks;
    }

    public function onTaskWasCreated(Events\TaskWasCreated $event)
    {
        $user = User::findOrFail($event->userId);

        $this->tasks->createForUser($user, $event->taskId, $event->taskName, Carbon::parse($event->createdAt));
    }

    public function onTaskWasCompleted(Events\TaskWasCompleted $event)
    {
        $task = $this->tasks->findByUuid($event->taskId);

        $this->tasks->markAsComplete($task, Carbon::parse($event->completedAt));
    }

    public function onTaskWasMarkedAsIncomplete(Events\TaskWasMarkedAsIncomplete $event)
    {
        $task = $this->tasks->findByUuid($event->taskId);

        $this->tasks->markAsIncomplete($task, Carbon::parse($event->date));
    }

    public function resetState()
    {
        Task::truncate();
    }
}
