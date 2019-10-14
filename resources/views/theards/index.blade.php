@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            @forelse($theards as $theard)
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="level">
                                <h4 class="flex">
                                    <a href="{{ $theard->path() }}">
                                        @if($theard->hasUpdatedFor(auth()->user()))
                                            <strong>{{ $theard->title }}</strong>
                                        @else
                                            {{ $theard->title }}
                                        @endif
                                    </a>
                                </h4>
                                <a href="#"><strong>{{ $theard->replies_count }} answers</strong></a>
                        </div>
                    </div>

                    <div class="card-body">  
                        <div class="body">{{ $theard->body }}</div>
                    </div>
                </div>
                @empty
                <p>
                    Empty list
                </p>
            @endforelse
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Tags
                </div>
                <div class="card-body">
                    @foreach($channels as $channel)
                        <a href="/questions/{{ $channel->slug }}" class="btn btn-sm btn-default">{{ $channel->name }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
