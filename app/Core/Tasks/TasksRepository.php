<?php

namespace App\Core\Tasks;

use App\User;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;

class TasksRepository
{
    public function createForUser(User $user, string $taskUuid, string $taskName, Carbon $createdAt)
    {
        $task = new Task([
            'uuid' => $taskUuid,
            'name' => $taskName,
            'created_at' => $createdAt->copy(),
            'updated_at' => $createdAt->copy(),
            'user_id' => $user->id,
        ]);

        $task->save();
    }

    public function markAsComplete(Task $task, Carbon $completedAt)
    {
        $task->forceFill([
            'completed_at' => $completedAt->copy(),
            'updated_at' => $completedAt->copy(),
        ])->save();
    }

    public function markAsIncomplete(Task $task, Carbon $date)
    {
        $task->forceFill([
            'updated_at' => $date->copy(),
            'completed_at' => null,
        ])->save();
    }

    public function findByUuid(string $taskUuid): Task
    {
        return Task::findByUuidOrFail($taskUuid);
    }

    public function newId(): string
    {
        return Str::uuid()->toString();
    }

    public function getIncompleteTasksForUser(int $userId): Collection
    {
        return Task::query()
            ->incomplete()
            ->latest()
            ->where('user_id', $userId)
            ->get();
    }

    public function getCompletedTasksForUser(int $userId): Collection
    {
        return Task::query()
            ->completed()
            ->latest('completed_at')
            ->where('user_id', $userId)
            ->get();
    }

    public function getCompletedTasksCountForUser(int $userId): int
    {
        return Task::query()
            ->completed()
            ->where('user_id', $userId)
            ->count();
    }
}
