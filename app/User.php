<?php

namespace App;

use Illuminate\Notifications\Notifiable;
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
        'password', 'remember_token', 'email'
    ];

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function theards()
    {
        return $this->hasMany(Theard::class)->latest();
    }

    public function activity()
    {
        return $this->hasMany(Activity::class);
    }

    public function read($theard)
    {
        cache()->forever(
            $this->visitedTheardCache($theard),
            \Carbon\Carbon::now()
        );
    }

    public function visitedTheardCache($theard)
    {
        return sprintf('users.%s.visits.%s', $this->id, $theard->id);
    }
}
