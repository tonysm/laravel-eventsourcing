<?php

namespace App\Core\Tasks;

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

    public function onTaskWasCreated(Events\TaskWasCreated $event)
    {
        $event->user->tasks()->create([
            'uuid' => $event->taskId,
            'name' => $event->taskName,
            'created_at' => Carbon::parse($event->createdAt)
        ]);
    }

    public function onTaskWasCompleted(Events\TaskWasCompleted $event)
    {
        $task = Task::findByUuidOrFail($event->taskId);

        $completedAt = Carbon::parse($event->completedAt);

        $task->forceFill([
                'completed_at' => $completedAt,
                'updated_at' => $completedAt,
            ])
            ->save();
    }

    public function onTaskWasMarkedAsIncomplete(Events\TaskWasMarkedAsIncomplete $event)
    {
        $task = Task::findByUuidOrFail($event->taskId);

        $task->forceFill([
                'completed_at' => null,
                'updated_at' => Carbon::parse($event->date),
            ])
            ->save();
    }

    public function resetState()
    {
        Task::truncate();
    }
}
