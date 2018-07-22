<?php

namespace Tests\Feature\Core\Tasks;

use App\User;
use Tests\TestCase;
use App\Core\Tasks\Task;
use App\Notifications\SecondCompletedTask;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatesTask()
    {
        $user = factory(User::class)->create();

        $task = Task::createTaskForUser(
            $user,
            'Get Milk'
        );

        $this->assertNotNull($task);
        $this->assertEquals('Get Milk', $task->name);
        $this->assertTrue($task->user->is($user));
    }

    public function testCreatedTaskIsNotCompletedByDefault()
    {
        $user = factory(User::class)->create();

        $task = Task::createTaskForUser(
            $user,
            'Get Milk'
        );

        $this->assertNull($task->completed_at);
    }

    public function testCanCompleteTask()
    {
        $user = factory(User::class)->create();

        $task = Task::createTaskForUser(
            $user,
            'Get Milk'
        );

        $task->markAsCompleted();

        $this->assertNotNull($task->completed_at);
    }

    public function testCanMarkCompletedTaskAsIncomplete()
    {
        $user = factory(User::class)->create();

        $task = Task::createTaskForUser(
            $user,
            'Get Milk'
        );

        $task->markAsCompleted();
        $task->markAsIncomplete();

        $this->assertNull($task->completed_at);
    }

    public function testSendsNotificationToUsersOnSecondCompletedTask()
    {
        Notification::fake();
        $user = factory(User::class)->create();

        Task::createTaskForUser($user, 'Get Milk')->markAsCompleted();
        Task::createTaskForUser($user, 'Get Milk B')->markAsCompleted();

        Notification::assertSentTo($user, SecondCompletedTask::class);
        $this->assertNotNull($user->refresh()->second_completed_task_at);
    }

    public function testOnlySendsOnce()
    {
        Notification::fake();
        $user = factory(User::class)->create();

        Task::createTaskForUser($user, 'Get Milk')->markAsCompleted();
        Task::createTaskForUser($user, 'Get Milk B')
            ->markAsCompleted()
            ->markAsIncomplete()
            ->markAsCompleted();

        Notification::assertSentToTimes($user, SecondCompletedTask::class, 1);
    }
}
