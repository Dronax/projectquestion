<?php

namespace App\Http\Controllers;

use App\Theard;
use App\Reply;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    public function index($channelId, Theard $theard)
    {
        return $theard->replies()->paginate(15);
    }

    public function store($channelId, Theard $theard)
    {
        $this->validate(request(), [
            'body' => 'required'
        ]);

        $reply = $theard->addReply([
            'body' => request('body'),
            'user_id' => auth()->user()->id,
        ]);

        if (request()->expectsJson()) {
            return $reply->load('owner');
        }

        return back()->with('flash', 'Ваш ответ был добавлен!');
    }

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->update(request(['body']));
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Reply is deleted!']);
        }

        return back();
    }
}
