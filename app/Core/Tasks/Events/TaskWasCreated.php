<?php

namespace App\Core\Tasks\Events;

use App\User;
use Illuminate\Queue\SerializesModels;
use Spatie\EventProjector\ShouldBeStored;

class TaskWasCreated implements ShouldBeStored
{
    use SerializesModels;

    /**
     * @var User
     */
    public $user;

    /**
     * @var string
     */
    public $taskId;

    /**
     * @var string
     */
    public $taskName;

    /**
     * Create a new event instance.
     *
     * @param User $user
     * @param string $taskId
     * @param string $taskName
     */
    public function __construct(User $user, string $taskId, string $taskName)
    {
        $this->user = $user;
        $this->taskId = $taskId;
        $this->taskName = $taskName;
    }
}
