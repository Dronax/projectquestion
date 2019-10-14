<?php

namespace App;

use App\Traits\Favoritable;
use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordsActivity;

class Reply extends Model
{
    use Favoritable, RecordsActivity;

    protected $guarded = [];

    protected $with = ['owner', 'favorites'];

    protected $appends = ['favoritesCount', 'isFavorited'];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($reply) {
            $reply->theard->increment('replies_count');
        });

        static::deleted(function ($reply) {
            $reply->theard->decrement('replies_count');
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function theard()
    {
        return $this->belongsTo(Theard::class);
    }

    public function path()
    {
        return $this->theard->path() . '#reply-' . $this->id;
    }
}
