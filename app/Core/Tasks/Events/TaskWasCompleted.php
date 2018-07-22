<?php

namespace App\Core\Tasks\Events;

use Illuminate\Support\Carbon;
use Illuminate\Queue\SerializesModels;
use Spatie\EventProjector\ShouldBeStored;

class TaskWasCompleted implements ShouldBeStored
{
    use SerializesModels;

    /**
     * @var string
     */
    public $taskId;

    /**
     * @var string
     */
    public $completedAt;

    /**
     * Create a new event instance.
     *
     * @param string $taskId
     * @param string $completedAt
     */
    public function __construct(string $taskId, string $completedAt)
    {
        $this->taskId = $taskId;
        $this->completedAt = $completedAt;
    }
}
