<?php

namespace Tests\Feature\Core\Tasks;

use App\Core\Tasks\TaskContext;
use App\User;
use Tests\TestCase;
use App\Core\Tasks\Task;
use App\Notifications\SecondCompletedTask;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var TaskContext
     */
    private $taskContext;

    public function setUp()
    {
        parent::setUp();

        $this->taskContext = $this->app->make(TaskContext::class);
    }

    public function testCreatesTask()
    {
        $user = factory(User::class)->create();

        $task = $this->taskContext->createTask($user->id, 'Get Milk');

        $this->assertNotNull($task);
        $this->assertEquals('Get Milk', $task->name);
        $this->assertEquals($user->id, $task->user_id);
    }

    public function testCreatedTaskIsNotCompletedByDefault()
    {
        $user = factory(User::class)->create();

        $task = $this->taskContext->createTask($user->id, 'Get Milk');

        $this->assertNull($task->completed_at);
    }

    public function testCanCompleteTask()
    {
        $user = factory(User::class)->create();

        $task = $this->taskContext->createTask($user->id, 'Get Milk');
        $task = $this->taskContext->completeTask($task->uuid);

        $this->assertNotNull($task->completed_at);
    }

    public function testCanMarkCompletedTaskAsIncomplete()
    {
        $user = factory(User::class)->create();

        $task = $this->taskContext->createTask($user->id, 'Get Milk');
        $task = $this->taskContext->completeTask($task->uuid);
        $task = $this->taskContext->undoTaskCompleted($task->uuid);

        $this->assertNull($task->completed_at);
    }

    public function testSendsNotificationToUsersOnSecondCompletedTask()
    {
        Notification::fake();
        $user = factory(User::class)->create();

        $taskA = $this->taskContext->createTask($user->id, 'Get Milk');
        $this->taskContext->completeTask($taskA->uuid);
        $taskB = $this->taskContext->createTask($user->id, 'Get Milk');
        $this->taskContext->completeTask($taskB->uuid);

        Notification::assertSentTo($user, SecondCompletedTask::class);
        $this->assertNotNull($user->refresh()->second_completed_task_at);
    }

    public function testOnlySendsOnce()
    {
        Notification::fake();
        $user = factory(User::class)->create();

        $taskA = $this->taskContext->createTask($user->id, 'Get Milk');
        $this->taskContext->completeTask($taskA->uuid);
        $taskB = $this->taskContext->createTask($user->id, 'Get Milk');
        $this->taskContext->completeTask($taskB->uuid);
        $this->taskContext->undoTaskCompleted($taskB->uuid);
        $this->taskContext->completeTask($taskB->uuid);

        Notification::assertSentToTimes($user, SecondCompletedTask::class, 1);
    }
}
