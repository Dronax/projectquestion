@extends('layouts.app')

@section('content')
<theard-view :initial-replies-count="{{ $theard->replies_count }}" inline-template>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header">
                    <div class="level">
                        <span class="flex">
                            <a href="{{ route('profile', $theard->creator->name) }}">{{ $theard->creator->name }}</a>
                            published:
                            {{ $theard->title }}
                        </span>
                        @can("update", $theard)
                        <form action="{{ $theard->path() }}" method="post">
                            @csrf
                            {{ method_field('DELETE') }}

                            <button class="btn btn-link" type="submit">Delete question</button>
                        </form>
                        @endcan
                    </div>
                </div>

                <div class="card-body">
                    {{ $theard->body }}
                </div>
            </div>
        
            <replies
                @added="repliesCount++"
                @removed="repliesCount--"></replies>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <p>
                        This question was created {{ $theard->created_at->diffForHumans() }}, by user: <a href="#">{{ $theard->creator->name }}</a> and has <span v-text="repliesCount"></span> {{ str_plural('answer', $theard->replies_count) }}.
                    </p>
                    @auth
                    <p>
                        <subscribe-button :active="{{ json_encode($theard->isSubscribedTo) }}"></subscribe-button>
                    </p>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
</theard-view>
@endsection
