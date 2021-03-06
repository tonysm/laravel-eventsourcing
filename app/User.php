<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use App\Notifications\SecondCompletedTask;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = [
        'second_completed_task_at',
    ];

    public function sendCongratulationsOnSecondTask()
    {
        $this->notify(new SecondCompletedTask());

        $this->forceFill(['second_completed_task_at' => now()])->save();
    }
}
