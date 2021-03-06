<?php

namespace App\Core\Tasks\Events;

use App\User;
use Illuminate\Queue\SerializesModels;
use Spatie\EventProjector\ShouldBeStored;

class TaskWasCreated implements ShouldBeStored
{
    use SerializesModels;

    /**
     * @var int
     */
    public $userId;

    /**
     * @var string
     */
    public $taskId;

    /**
     * @var string
     */
    public $taskName;

    /**
     * @var string
     */
    public $createdAt;

    /**
     * Create a new event instance.
     *
     * @param int $userId
     * @param string $taskId
     * @param string $taskName
     * @param string $createdAt
     */
    public function __construct(int $userId, string $taskId, string $taskName, string $createdAt)
    {
        $this->userId = $userId;
        $this->taskId = $taskId;
        $this->taskName = $taskName;
        $this->createdAt = $createdAt;
    }
}
