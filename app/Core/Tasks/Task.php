<?php

namespace App\Core\Tasks;

use App\User;
use Illuminate\Support\Str;
use App\Core\Entities\UuidModel;
use Illuminate\Database\Eloquent\Model;
use App\Core\Tasks\Events\TaskWasCreated;
use App\Core\Tasks\Events\TaskWasCompleted;
use App\Core\Tasks\Events\TaskWasMarkedAsIncomplete;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use UuidModel;

    protected $fillable = [
        'uuid',
        'name',
        'created_at',
        'updated_at',
    ];

    protected $dates = [
        'completed_at',
    ];

    public function scopeIncomplete($query)
    {
        $query->whereNull('completed_at');
    }

    public function scopeCompleted($query)
    {
        $query->whereNotNull('completed_at');
    }

    public static function createTaskForUser(User $user, string $taskName): Task
    {
        $taskId = Str::uuid()->toString();

        event(new TaskWasCreated(
            $user,
            $taskId,
            $taskName,
            now()->toAtomString()
        ));

        return static::findByUuidOrFail($taskId);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function markAsCompleted()
    {
        event(new TaskWasCompleted(
            $this->uuid,
            now()->toAtomString()
        ));

        return $this->refresh();
    }

    public function markAsIncomplete()
    {
        event(new TaskWasMarkedAsIncomplete(
            $this->uuid,
            now()->toAtomString()
        ));

        return $this->refresh();
    }
}
