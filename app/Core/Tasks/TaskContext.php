<?php

namespace App\Core\Tasks;

use App\Core\Tasks\Events;
use Illuminate\Database\Eloquent\Collection;

class TaskContext
{
    /**
     * @var TasksRepository
     */
    private $tasks;

    public function __construct(TasksRepository $tasks)
    {
        $this->tasks = $tasks;
    }

    public function createTask(int $userId, string $taskName): Task
    {
        $taskId = $this->tasks->newId();

        event(new Events\TaskWasCreated(
            $userId,
            $taskId,
            $taskName,
            now()->toAtomString()
        ));

        return $this->tasks->findByUuid($taskId);
    }

    public function completeTask(string $taskUuid): Task
    {
        event(new Events\TaskWasCompleted(
            $taskUuid,
            now()->toAtomString()
        ));

        return $this->tasks->findByUuid($taskUuid);
    }

    public function undoTaskCompleted(string $taskUuid): Task
    {
        event(new Events\TaskWasMarkedAsIncomplete(
            $taskUuid,
            now()->toAtomString()
        ));

        return $this->tasks->findByUuid($taskUuid);
    }

    public function getIncompleteTasksForUser(int $userId): Collection
    {
        return $this->tasks->getIncompleteTasksForUser($userId);
    }

    public function getCompletedTasksForUser(int $userId): Collection
    {
        return $this->tasks->getCompletedTasksForUser($userId);
    }
}
