@component('profiles.activities.activity')
    @slot('heading')
      <a href="{{ $activity->subject->favorited->path() }}">{{ $profileUser->name }} добавил в избранное ответ {{ $activity->subject->favorited->title }}</a>
    @endslot

    @slot('body')
      {{ $activity->subject->favorited->body }}
    @endslot
@endcomponent