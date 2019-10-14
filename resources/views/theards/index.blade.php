@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
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
                                <a href="#"><strong>{{ $theard->replies_count }} ответов</strong></a>
                        </div>
                    </div>

                    <div class="card-body">  
                        <div class="body">{{ $theard->body }}</div>
                    </div>
                </div>
                @empty
                <p>
                    Пока нет никаких результатов по вашему запросу!
                </p>
            @endforelse
        </div>
    </div>
</div>
@endsection
