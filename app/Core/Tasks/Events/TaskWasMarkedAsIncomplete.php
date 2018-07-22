<?php

namespace App\Core\Tasks\Events;

use Illuminate\Queue\SerializesModels;
use Spatie\EventProjector\ShouldBeStored;

class TaskWasMarkedAsIncomplete implements ShouldBeStored
{
    use SerializesModels;

    /**
     * @var string
     */
    public $taskId;

    /**
     * @var string
     */
    public $date;

    /**
     * Create a new event instance.
     *
     * @param string $taskId
     * @param string $date
     */
    public function __construct(string $taskId, string $date)
    {
        $this->taskId = $taskId;
        $this->date = $date;
    }
}
