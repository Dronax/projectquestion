<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Notifications\TheardWasUpdated;

class TheardSubscription extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function theard()
    {
        return $this->belongsTo('App\Theard');
    }

    public function notify($reply)
    {
        $this->user->notify(new TheardWasUpdated($this->theard, $reply));
    }
}
