@component('profiles.activities.activity')
    @slot('heading')
      {{ $profileUser->name }} published answer on question <a href="{{ $activity->subject->theard->path() }}">{{ $activity->subject->theard->title }}</a>
    @endslot

    @slot('body')
      {{ $activity->subject->body }}
    @endslot
@endcomponent