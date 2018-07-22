<?php

namespace App\Core\Tasks;

use App\Core\Entities\UuidModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Task (ReadModel)
 *
 * @package App\Core\Tasks
 */
class Task extends Model
{
    use UuidModel;

    protected $fillable = [
        'uuid',
        'name',
        'created_at',
        'updated_at',
        'user_id',
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
}
