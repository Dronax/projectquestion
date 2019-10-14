<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordsActivity;

class Theard extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    protected $with = ['creator', 'channel'];

    protected $appends = ['isSubscribedTo'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($theard) {
            $theard->replies->each->delete();
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function path()
    {
        return '/questions/' . $this->slug;
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

        $this->notifySubscribers($reply);

        return $reply;
    }

    public function notifySubscribers($reply)
    {
        $this->subscriptions
            ->where('user_id', '!=', $reply->user_id)
            ->each->notify($reply);
    }

    public function scopeFilter($query, $filters)
    {
        $filters->apply($query);
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()
            ->create(['user_id' => $userId ?: auth()->user()->id]);

        return $this;
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->user()->id)
            ->delete();
    }

    public function getIsSubscribedToAttribute()
    {
        if (auth()->guest()) {
            return;
        }
        return $this->subscriptions()->where('user_id', auth()->user()->id)->exists();
    }

    public function subscriptions()
    {
        return $this->hasMany(TheardSubscription::class);
    }

    public function hasUpdatedFor($user)
    {
        if (auth()->guest()) {
            return;
        }

        $key = $user->visitedTheardCache($this);

        return $this->updated_at > cache($key);
    }
}
