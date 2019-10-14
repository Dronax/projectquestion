<?php

namespace App\Http\Controllers;

use App\Theard;
use App\Channel;
use App\Filters\TheardFilters;
use Illuminate\Http\Request;

class TheardsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, TheardFilters $filters)
    {
        $theards = $this->getTheards($channel, $filters);

        if (request()->wantsJson()) {
            return $theards;
        }

        return view('theards.index', compact('theards'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('theards.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'body' => 'required',
            'channel_id' => 'required|exists:channels,id'
        ]);

        $theard = Theard::create([
            'user_id' => auth()->user()->id,
            'channel_id' => $request->channel_id,
            'title' => $request->title,
            'body' => $request->body
        ]);

        return redirect($theard->path())->with('flash', 'Your question was created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Theard  $theard
     * @return \Illuminate\Http\Response
     */
    public function show($channelId, Theard $theard)
    {
        if (auth()->check()) {
            auth()->user()->read($theard);
        }

        return view('theards.show', compact('theard'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Theard  $theard
     * @return \Illuminate\Http\Response
     */
    public function edit(Theard $theard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Theard  $theard
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Theard $theard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Theard  $theard
     * @return \Illuminate\Http\Response
     */
    public function destroy($channel, Theard $theard)
    {
        $this->authorize('update', $theard);

        $theard->delete();

        if (request()->wantsJson()) {
            return response([], 204);
        }

        return redirect('/questions');
    }

    protected function getTheards(Channel $channel, TheardFilters $filters)
    {
        $theards = Theard::latest()->filter($filters);

        if ($channel->exists) {
            $theards->where('channel_id', $channel->id);
        }

        return $theards->get();
    }
}
